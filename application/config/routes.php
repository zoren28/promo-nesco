<?php
defined('BASEPATH') or exit('No direct script access allowed');

// for user account module
$route['company_list'] = 'placement/setup/company_list';
$route['delete_company'] = 'placement/setup/delete_company';
$route['update_company_status'] = 'placement/setup/update_company_status';
$route['show_company'] = 'placement/setup/show_company';
$route['update_company'] = 'placement/setup/update_company';
$route['store_company'] = 'placement/setup/store_company';
$route['agency_list'] = 'placement/setup/agency_list';
$route['delete_agency'] = 'placement/setup/delete_agency';
$route['agency_status'] = 'placement/setup/agency_status';
$route['show_agency'] = 'placement/setup/show_agency';
$route['update_agency'] = 'placement/setup/update_agency';
$route['store_agency'] = 'placement/setup/store_agency';
$route['companies_for_agency'] = 'placement/setup/companies_for_agency';
$route['untag_company_agency'] = 'placement/setup/untag_company_agency';
$route['choose_agency'] = 'placement/setup/choose_agency';
$route['tag_company_agency'] = 'placement/setup/tag_company_agency';
$route['store_promo_locate_company'] = 'placement/setup/store_promo_locate_company';
$route['product_list'] = 'placement/setup/product_list';
$route['delete_product'] = 'placement/setup/delete_product';
$route['update_product_status'] = 'placement/setup/update_product_status';
$route['show_product'] = 'placement/setup/show_product';
$route['update_product'] = 'placement/setup/update_product';
$route['store_product'] = 'placement/setup/store_product';
$route['products_under_company'] = 'placement/setup/products_under_company';
$route['untag_product_company'] = 'placement/setup/untag_product_company';
$route['choose_company'] = 'placement/setup/choose_company';
$route['tag_product_company'] = 'placement/setup/tag_product_company';
$route['store_promo_company_products'] = 'placement/setup/store_promo_company_products';

// for user account module
$route['create_user_account'] = 'placement/account/create_user_account';
$route['find_active_hr_staff'] = 'placement/account/find_active_hr_staff';
$route['create_hr_account'] = 'placement/account/create_hr_account';
$route['promo_account_list'] = 'placement/account/promo_account_list';
$route['hr_account_list'] = 'placement/account/hr_account_list';
$route['update_hr_account'] = 'placement/account/update_hr_account';
$route['update_hr_status'] = 'placement/account/update_hr_status';
$route['update_user_access'] = 'placement/account/update_user_access';

// for contract module
$route['extend_contract'] = 'placement/contract/extend_contract';
$route['show_intro'] = 'placement/contract/show_intro';
$route['find_witness'] = 'placement/contract/find_witness';
$route['find_iextend_promo'] = 'placement/contract/find_iextend_promo';
$route['process_renewal'] = 'placement/contract/process_renewal';
$route['print_contract_permit/(:any)'] = 'placement/contract/print_contract_permit/$1';
$route['print_permit_renewal/(:any)'] = 'placement/contract/print_permit_renewal/$1';
$route['store_duty_details'] = 'placement/contract/store_duty_details';
$route['print_contract_renewal/(:any)'] = 'placement/contract/print_contract_renewal/$1';
$route['store_witness_otherdetails'] = 'placement/contract/store_witness_otherdetails';
$route['other_details_form'] = 'placement/contract/other_details_form';
$route['print_current_permit'] = 'placement/contract/print_current_permit';
$route['find_iprintpermit_promo'] = 'placement/contract/find_iprintpermit_promo';
$route['current_permit_form/(:any)'] = 'placement/contract/current_permit_form/$1';
$route['print_previous_permit'] = 'placement/contract/print_previous_permit';
$route['display_previous_contract/(:any)'] = 'placement/contract/display_previous_contract/$1';
$route['display_previous_permit'] = 'placement/contract/display_previous_permit';
$route['transfer_rate_form/(:any)'] = 'placement/contract/transfer_rate_form/$1';
$route['transfer_rate'] = 'placement/contract/transfer_rate';
$route['eoc_list'] = 'placement/contract/eoc_list';
$route['upload_clearance_renewal'] = 'placement/contract/upload_clearance_renewal';
$route['store_clearance_renewal'] = 'placement/contract/store_clearance_renewal';

// for outlet module
$route['find_active_promo'] = 'placement/employee/find_active_promo';
$route['promo_details'] = 'placement/outlet/promo_details';
$route['add_outlet_form'] = 'placement/outlet/add_outlet_form';
$route['add_new_outlet'] = 'placement/outlet/add_new_outlet';
$route['change_outlet_histories'] = 'placement/outlet/change_outlet_histories';
$route['find_active_station_promo'] = 'placement/employee/find_active_station_promo';
$route['find_active_roving_promo'] = 'placement/employee/find_active_roving_promo';
$route['transfer_outlet_form'] = 'placement/outlet/transfer_outlet_form';
$route['store_clearance_form'] = 'placement/outlet/store_clearance_form';
$route['uploadClearance'] = 'placement/outlet/uploadClearance';
$route['transfer_details_form'] = 'placement/outlet/transfer_details_form';
$route['transfer_outlet'] = 'placement/outlet/transfer_outlet';
$route['remove_outlet_form'] = 'placement/outlet/remove_outlet_form';
$route['remove_outlet'] = 'placement/outlet/remove_outlet';

// for report module
$route['view_stat_BU'] = 'placement/report/view_stat_BU';
$route['load_stat_BU'] = 'placement/report/load_stat_BU';
$route['view_stat_dept'] = 'placement/report/view_stat_dept';
$route['load_stat_dept'] = 'placement/report/load_stat_dept';
$route['select_company_under_agency'] = 'placement/report/select_company_under_agency';
$route['report/qbe_report'] = 'placement/report/qbe_report';
$route['termination_list'] = 'placement/report/termination_list';
$route['eoc_employees'] = 'placement/report/eoc_employees';

// for dashboard module
$route['new_employee'] = 'placement/dashboard/new_employee';
$route['birthday_today'] = 'placement/dashboard/birthday_today';
$route['active_employee'] = 'placement/dashboard/active_employee';
$route['eoc_today'] = 'placement/dashboard/eoc_today';
$route['due_contract'] = 'placement/dashboard/due_contract';
$route['fetch_birthday_today'] = 'placement/dashboard/fetch_birthday_today';
$route['fetch_due_contract'] = 'placement/dashboard/fetch_due_contract';
$route['due_contract_xls'] = 'placement/dashboard/due_contract_xls';

// for employee module
$route['find_hr_staff'] = 'placement/employee/find_hr_staff';
$route['fetch_employee_masterfile'] = 'placement/employee/fetch_employee_masterfile';
$route['fetch_assigned_department/(:any)'] = 'placement/employee/fetch_assigned_department/$1';
$route['employee/search_employee'] = 'placement/employee/search_employee';
$route['searchThis'] = 'placement/employee/searchThis';
$route['search_applicant'] = 'placement/employee/search_applicant';
$route['employee_information_details/(:any)'] = 'placement/employee/employee_information_details/$1';
$route['find_mothers_name'] = 'placement/employee/find_mothers_name';
$route['view_birthCert'] = 'placement/employee/view_birthCert';
$route['get_age'] = 'placement/employee/get_age';
$route['update_birthCertForm'] = 'placement/employee/update_birthCertForm';
$route['updateScannedNSO'] = 'placement/employee/updateScannedNSO';
$route['add_children_info'] = 'placement/employee/add_children_info';
$route['delete_children_info'] = 'placement/employee/delete_children_info';
$route['submit_children_info'] = 'placement/employee/submit_children_info';
$route['upload_birthCertForm'] = 'placement/employee/upload_birthCertForm';
$route['update_children_info'] = 'placement/employee/update_children_info';
$route['submit_spouse_children'] = 'placement/employee/submit_spouse_children';
$route['locate_business_unit'] = 'placement/employee/locate_business_unit';
$route['locate_department'] = 'placement/employee/locate_department';
$route['locate_section'] = 'placement/employee/locate_section';
$route['locate_sub_section'] = 'placement/employee/locate_sub_section';
$route['locate_unit'] = 'placement/employee/locate_unit';
$route['position_level'] = 'placement/employee/position_level';
$route['contract_duration'] = 'placement/employee/contract_duration';

// update employee info
$route['update_basicinfo'] = 'placement/employee/update_basicinfo';
$route['update_family'] = 'placement/employee/update_family';
$route['update_contact'] = 'placement/employee/update_contact';
$route['update_educ'] = 'placement/employee/update_educ';
$route['seminar_form'] = 'placement/employee/seminar_form';
$route['submitSeminar'] = 'placement/employee/submitSeminar';
$route['seminarCertificate'] = 'placement/employee/seminarCertificate';
$route['character_ref_form'] = 'placement/employee/character_ref_form';
$route['submit_character_ref'] = 'placement/employee/submit_character_ref';
$route['update_skills'] = 'placement/employee/update_skills';
$route['appraisal_details'] = 'placement/employee/appraisal_details';
$route['examDetails'] = 'placement/employee/examDetails';
$route['appHistDetails'] = 'placement/employee/appHistDetails';
$route['interviewDetails'] = 'placement/employee/interviewDetails';
$route['update_apphis'] = 'placement/employee/update_apphis';
$route['contractDetails'] = 'placement/employee/contractDetails';
$route['promoContractDetails'] = 'placement/employee/promoContractDetails';
$route['get_file'] = 'placement/employee/get_file';
$route['promoFile'] = 'placement/employee/promoFile';
$route['editContractDetails'] = 'placement/employee/editContractDetails';
$route['updateContractDetails'] = 'placement/employee/updateContractDetails';
$route['updatePromoContract'] = 'placement/employee/updatePromoContract';
$route['editPromoContractDetails'] = 'placement/employee/editPromoContractDetails';
$route['updatePromoContractDetails'] = 'placement/employee/updatePromoContractDetails';
$route['uploadScannedFileForm'] = 'placement/employee/uploadScannedFileForm';
$route['uploadPromoScannedFileForm'] = 'placement/employee/uploadPromoScannedFileForm';
$route['uploadScannedFile'] = 'placement/employee/uploadScannedFile';
$route['uploadPromoScannedFile'] = 'placement/employee/uploadPromoScannedFile';
$route['addEmploymentHist'] = 'placement/employee/addEmploymentHist';
$route['employmentCertificate'] = 'placement/employee/employmentCertificate';
$route['submitEmploymentHist'] = 'placement/employee/submitEmploymentHist';
$route['viewJobTrans'] = 'placement/employee/viewJobTrans';
$route['addBlacklist'] = 'placement/employee/addBlacklist';
$route['submitBlacklist'] = 'placement/employee/submitBlacklist';
$route['update_benefits'] = 'placement/employee/update_benefits';
$route['view201File'] = 'placement/employee/view201File';
$route['upload201Files'] = 'placement/employee/upload201Files';
$route['upload201File'] = 'placement/employee/upload201File';
$route['removeSubordinates'] = 'placement/employee/removeSubordinates';
$route['addSupervisor'] = 'placement/employee/addSupervisor';
$route['selectSupervisor'] = 'placement/employee/selectSupervisor';
$route['saveSupervisor'] = 'placement/employee/saveSupervisor';
$route['update_remarks'] = 'placement/employee/update_remarks';
$route['resetPass'] = 'placement/employee/resetPass';
$route['activateAccount'] = 'placement/employee/activateAccount';
$route['deactivateAccount'] = 'placement/employee/deactivateAccount';
$route['deleteAccount'] = 'placement/employee/deleteAccount';
$route['addUserAccount'] = 'placement/employee/addUserAccount';
$route['submitAccount'] = 'placement/employee/submitAccount';
$route['changeProfilePic'] = 'placement/employee/changeProfilePic';
$route['getProfilePic'] = 'placement/employee/getProfilePic';
$route['uploadProfilePic'] = 'placement/employee/uploadProfilePic';
$route['addContract'] = 'placement/employee/addContract';

// select company, business_unit, department, vendor, product
$route['select_agency'] = 'placement/employee/select_agency';
$route['select_department'] = 'placement/employee/select_department';
$route['select_cutoff'] = 'placement/employee/select_cutoff';
$route['select_position'] = 'placement/employee/select_position';
$route['select_position_level'] = 'placement/employee/select_position_level';
$route['select_employee_type'] = 'placement/employee/select_employee_type';
$route['select_promo_products'] = 'placement/employee/select_promo_products';
$route['select_promo_type'] = 'placement/employee/select_promo_type';
$route['load_vendor'] = 'placement/employee/load_vendor';
$route['load_products'] = 'placement/employee/load_products';
$route['load_business_unit'] = 'placement/employee/load_business_unit';
$route['load_department'] = 'placement/employee/load_department';
$route['load_promo_business_unit'] = 'placement/employee/load_promo_business_unit';
$route['load_promo_intro'] = 'placement/employee/load_promo_intro';

$route['select_company'] = 'placement/employee/select_company';
$route['select_product'] = 'placement/employee/select_product';
$route['select_business_unit'] = 'placement/employee/select_business_unit';
$route['locate_promo_department'] = 'placement/employee/locate_promo_department';
$route['select_vendor'] = 'placement/employee/select_vendor';

// for blacklisted module
$route['fetch_blacklisted'] = 'placement/blacklist/fetch_blacklisted';
$route['update_blacklist_form'] = 'placement/blacklist/update_blacklist_form';
$route['update_blacklist'] = 'placement/blacklist/update_blacklist';
$route['candidate_for_blacklisted'] = 'placement/blacklist/candidate_for_blacklisted';
$route['browseNames'] = 'placement/blacklist/browse_names';
$route['add_blacklist'] = 'placement/blacklist/add_blacklist';

// for initial requirement in recruitment process
$route['check_applicant_duplicate_or_blacklist'] = 'recruitment/initial/check_applicant_duplicate_or_blacklist';
$route['upload_initial'] = 'recruitment/initial/upload_initial';
$route['applicant_information'] = 'recruitment/initial/applicant_information';
$route['proceed_record_applicants'] = 'recruitment/initial/proceed_record_applicants';
$route['applicant_examination_setup'] = 'recruitment/initial/applicant_examination_setup';
$route['view_exam_setup'] = 'recruitment/initial/view_exam_setup';
$route['setup_examination'] = 'recruitment/initial/setup_examination';
$route['save_examination'] = 'recruitment/initial/save_examination';
$route['append_character_ref'] = 'recruitment/initial/append_character_ref';

$route['tag_applicant_interview'] = 'recruitment/initial/tag_applicant_interview';
$route['tag_applicant_transfer'] = 'recruitment/initial/tag_applicant_transfer';
$route['initial_interview'] = 'recruitment/initial/initial_interview';
$route['save_initial_interview'] = 'recruitment/initial/save_initial_interview';
$route['setup_interview'] = 'recruitment/initial/setup_interview';
$route['setup_interviewee'] = 'recruitment/initial/setup_interviewee';
$route['check_interview'] = 'recruitment/initial/check_interview';
$route['final_interview'] = 'recruitment/initial/final_interview';
$route['hiring_setup'] = 'recruitment/initial/hiring_setup';
$route['final_completion'] = 'recruitment/initial/final_completion';
$route['save_final_completion'] = 'recruitment/initial/save_final_completion';
$route['company_select'] = 'recruitment/initial/company_select';

// for menu module
$route['logout'] = 'logout';
$route['placement/page/menu/(:any)/(:any)/(:any)'] = 'placement/page/menu/$1/$2/$3';
$route['recruitment/page/menu/(:any)/(:any)/(:any)'] = 'recruitment/page/menu/$1/$2/$3';
$route['placement'] = 'placement/page/menu';
$route['recruitment'] = 'recruitment/page/menu';
$route['default_controller'] = 'page';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
