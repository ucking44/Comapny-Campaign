<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LslCompanyMaster extends Model
{
    use HasFactory;

    protected $table = "lsl_company_master";

    protected $primaryKey = 'Company_id';

    protected $fillable = [
        'Company_name',
        'Company_type',
        'Solution_type',
        'Membership_generation',
        'Membership_no_series',
        'Country',
        'Region',
        'Zone',
        'State',
        'City',
        'Address',
        'Pin_code',
        'Company_person_name',
        'Company_person_email',
        'Company_person_phone_no',
        'Company_secondary_person_name',
        'CCompany_secondary_person_email',
        'Company_secondary_person_phone_no',
        'Website',
        'Company_logo',
        'Company_reg_no',
        'Comp_reg_date',
        'Points_value_definition',
        'Points_expiry_period',
        'Allow_Customer_password_expiry',
        'Customer_password_expiry_period',
        'E_voucher_expiry_period',
        'Pin_no_applicable',
        'Corporate_email_id',
        'VAT',
        'LSL_markup',
        'Referred_by',
        'Member_expiry',
        'E_voucher_llosource',
        'Order_no_series',
        'SMS_limit',
        'Available_sms',
        'Current_balance',
        'Domain',
        'Querylog_ticket',
        'Create_User_id',
        'Creation_date',
        'Update_User_id',
        'Update_date',
        'Active_flag'
    ];

}
