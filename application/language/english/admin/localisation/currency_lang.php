<?php
// Heading
$lang['heading_title']        = 'Currency';  

// Text
$lang['text_success']         = 'Success: You have modified currencies!';

// Column
$lang['column_title']         = 'Currency Title';
$lang['column_code']          = 'Code'; 
$lang['column_value']         = 'Value';
$lang['column_date_modified'] = 'Last Updated';
$lang['column_action']        = 'Action';

// Entry
$lang['entry_title']          = 'Currency Title:';
$lang['entry_code']           = 'Code:<br /><span class="help">Do not change if this is your default currency.</span>';
$lang['entry_value']          = 'Value:<br /><span class="help">Set to 1.00000 if this is your default currency.</span>';
$lang['entry_symbol_left']    = 'Symbol Left:';
$lang['entry_symbol_right']   = 'Symbol Right:';
$lang['entry_decimal_place']  = 'Decimal Places:';
$lang['entry_status']         = 'Status:';

// Error
$lang['error_permission']     = 'Warning: You do not have permission to modify currencies!';
$lang['error_title']          = 'Currency Title must be between 3 and 32 characters!';
$lang['error_code']           = 'Currency Code must contain 3 characters!';
$lang['error_default']        = 'Warning: This currency cannot be deleted as it is currently assigned as the default store currency!';
$lang['error_store']          = 'Warning: This currency cannot be deleted as it is currently assigned to %s stores!';
$lang['error_order']          = 'Warning: This currency cannot be deleted as it is currently assigned to %s orders!';
?>