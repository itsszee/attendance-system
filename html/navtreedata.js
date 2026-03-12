/*
 @licstart  The following is the entire license notice for the JavaScript code in this file.

 The MIT License (MIT)

 Copyright (C) 1997-2020 by Dimitri van Heesch

 Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 and associated documentation files (the "Software"), to deal in the Software without restriction,
 including without limitation the rights to use, copy, modify, merge, publish, distribute,
 sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all copies or
 substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
 BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

 @licend  The above is the entire license notice for the JavaScript code in this file
*/
var NAVTREE =
[
  [ "Attendance-System", "index.html", [
    [ "📋 Complete Changelog: Google Authentication &amp; Unified Auth UI", "md__c_h_a_n_g_e_l_o_g.html", [
      [ "🎯 Project Overview", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md1", null ],
      [ "📁 Files Created", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md3", [
        [ "1. Authentication Layout Component", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md4", null ],
        [ "2. Google OAuth Controller", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md5", null ],
        [ "3. Database Migration", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md6", null ],
        [ "4. Documentation Files", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md7", null ]
      ] ],
      [ "✏️ Files Modified", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md9", [
        [ "1. Authentication Views (UI Updated)", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md10", null ],
        [ "2. Configuration Files", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md11", null ],
        [ "3. Routing", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md12", null ],
        [ "4. User Model", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md13", null ]
      ] ],
      [ "🛠️ Database Schema Changes", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md15", [
        [ "users table (After Migration)", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md16", null ],
        [ "Table Structure:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md17", null ]
      ] ],
      [ "🔧 Configuration Details", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md19", [
        [ "services.php Addition:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md20", null ],
        [ ".env Requirements:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md21", null ]
      ] ],
      [ "🎨 UI/UX Improvements", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md23", [
        [ "Visual Features:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md24", null ],
        [ "Form Elements:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md25", null ],
        [ "Breakpoints:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md26", null ]
      ] ],
      [ "🔐 Security Features", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md28", null ],
      [ "📊 Statistics", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md30", [
        [ "Code Summary:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md31", null ],
        [ "Features Implemented:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md32", null ]
      ] ],
      [ "🚀 Performance Impact", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md34", [
        [ "Frontend:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md35", null ],
        [ "Backend:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md36", null ]
      ] ],
      [ "✅ Testing Checklist", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md38", [
        [ "Local Testing:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md39", null ],
        [ "Production Testing:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md40", null ]
      ] ],
      [ "📦 Dependencies", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md42", [
        [ "Already Installed:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md43", null ],
        [ "No New Dependencies Required!", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md44", null ]
      ] ],
      [ "📱 Responsive Design", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md46", [
        [ "Mobile (&lt; 480px):", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md47", null ],
        [ "Tablet (480px - 768px):", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md48", null ],
        [ "Desktop (768px+):", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md49", null ]
      ] ],
      [ "🔄 Migration Path", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md51", [
        [ "For Existing Users:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md52", null ],
        [ "For New Users:", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md53", null ]
      ] ],
      [ "📚 File Structure After Update", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md55", null ],
      [ "🎓 Learning Resources", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md57", null ],
      [ "✨ What's Next?", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md59", null ],
      [ "📞 Support", "md__c_h_a_n_g_e_l_o_g.html#autotoc_md61", null ]
    ] ],
    [ "🚀 Quick Start: Google Authentication", "md__q_u_i_c_k___s_t_a_r_t.html", [
      [ "⚡ 5 Minute Setup", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md65", [
        [ "Step 1: Get Google OAuth Credentials (5 min)", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md66", null ],
        [ "Step 2: Update .env File (1 min)", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md67", null ],
        [ "Step 3: Run Migration (1 min)", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md68", null ],
        [ "Step 4: Test (1 min)", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md69", null ]
      ] ],
      [ "📋 What Works Now", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md70", null ],
      [ "🎨 UI Features", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md71", null ],
      [ "📱 Pages", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md72", null ],
      [ "🔑 Controller Routes", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md73", null ],
      [ "🐛 Troubleshooting", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md74", null ],
      [ "🔒 Security Tips", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md75", null ],
      [ "📊 User Data Stored", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md76", null ],
      [ "🚀 Production Checklist", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md77", null ],
      [ "📚 Documentation", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md78", null ],
      [ "✨ Next Steps", "md__q_u_i_c_k___s_t_a_r_t.html#autotoc_md79", null ]
    ] ]
  ] ]
];

var NAVTREEINDEX =
[
"index.html"
];

var SYNCONMSG = 'click to disable panel synchronization';
var SYNCOFFMSG = 'click to enable panel synchronization';
var LISTOFALLMEMBERS = 'List of all members';