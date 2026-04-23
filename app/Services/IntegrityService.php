<?php

namespace App\Services;

use App\Models\PointRule;
use App\Models\PointLedger;
use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class IntegrityService
{
    /**
     * Mengevaluasi poin dan mengaplikasikan token pada waktu absensi
     */
    public function processAttendance($attendance, $user)
    {
        try {
            // 1. Token Interceptor 
            if ($attendance->status === 'late') {
                $token = UserToken::with('item')->where('user_id', $user->id)
                    ->where('status', 'AVAILABLE')
                    ->first(); // Memakai token pertama yang tersedia
    
                if ($token) {
                    // Update status token
                    $token->update([
                        'status' => 'USED',
                        'used_at_attendance_id' => $attendance->id
                    ]);
                    
                    // Update status attendance
                    $attendance->update([
                        'status' => 'on_time_token',
                        'approval_status' => 'approved' 
                    ]);
                    
                    $itemName = $token->item ? $token->item->item_name : 'Fleksibilitas';
                    $this->addLedgerEntry(
                        $user->id, 
                        'SPEND', 
                        0, 
                        "Token Dipakai: " . $itemName . " pada " . $attendance->check_in_at->format('d/m/Y H:i')
                    );
                    
                    // Kalo pakai token, tidak kena penalty rule, bisa langsung return.
                    return;
                }
            }
    
            // 2. Rule Engine (Kalkulasi Poin)
            $rules = PointRule::all();
            if ($rules->isEmpty()) {
                return; // Tidak ada rule
            }
            
            $checkInTime = Carbon::parse($attendance->check_in_at)->format('H:i:s');
            $userRole = $user->role; 
            
            $totalModifier = 0;
            $matchedRules = [];
    
            foreach ($rules as $rule) {
                // Cek Role Target
                if (!empty($rule->target_role) && $rule->target_role !== 'All' && strtolower($rule->target_role) !== strtolower($userRole)) {
                    continue;
                }
    
                $operator = $rule->condition_operator;
                $value = $rule->condition_value; 
                
                try {
                    $timeValue = Carbon::parse($value)->format('H:i:s');
                } catch (\Exception $e) {
                    $timeValue = $value; // Fallback jika bukan valid time
                }
                
                $isMatch = false;
    
                if ($operator === '<') {
                    $isMatch = $checkInTime < $timeValue;
                } elseif ($operator === '>') {
                    $isMatch = $checkInTime > $timeValue;
                } elseif ($operator === '<=') {
                    $isMatch = $checkInTime <= $timeValue;
                } elseif ($operator === '>=') {
                    $isMatch = $checkInTime >= $timeValue;
                } elseif ($operator === '=') {
                    $isMatch = $checkInTime === $timeValue;
                }
    
                if ($isMatch) {
                    $totalModifier += $rule->point_modifier;
                    $matchedRules[] = $rule->rule_name;
                }
            }
    
            if ($totalModifier !== 0) {
                $type = $totalModifier > 0 ? 'EARN' : 'PENALTY';
                $desc = "Rule Applied: " . implode(', ', $matchedRules) . " | Waktu: " . $attendance->check_in_at->format('d/m/Y H:i');
    
                $this->addLedgerEntry($user->id, $type, $totalModifier, $desc);
            }
        } catch (\Exception $e) {
            Log::error('IntegrityService Error: ' . $e->getMessage());
        }
    }

    /**
     * Helper untuk insert ke ledger dan kalkulasi balance terakhir
     */
    public function addLedgerEntry($userId, $type, $amount, $description = null)
    {
        $lastLedger = PointLedger::where('user_id', $userId)
            ->latest('id')
            ->first();

        $balance = $lastLedger ? $lastLedger->current_balance : 0;
        $newBalance = $balance + $amount;

        return PointLedger::create([
            'user_id' => $userId,
            'transaction_type' => $type,
            'amount' => $amount,
            'current_balance' => $newBalance,
            'description' => $description,
        ]);
    }
}