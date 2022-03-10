<?php
defined('BASEPATH') or exit('No direct script access allowed');

// for report module
$route['view_stat_BU'] = 'placement/report/view_stat_BU';
$route['load_stat_BU'] = 'placement/report/load_stat_BU';
$route['view_stat_dept'] = 'placement/report/view_stat_dept';
$route['load_stat_dept'] = 'placement/report/load_stat_dept';

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
$route['uploadScannedFile'] = 'placement/employee/uploadScannedFile';
$route['changeProfilePic'] = 'placement/employee/changeProfilePic';
$route['getProfilePic'] = 'placement/employee/getProfilePic';
$route['uploadProfilePic'] = 'placement/employee/uploadProfilePic';
$route['addContract'] = 'placement/employee/addContract';

// select company, business_unit, department, vendor, product
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

// for menu module
$route['logout'] = 'logout';
$route['placement/page/menu/(:any)/(:any)/(:any)'] = 'placement/page/menu/$1/$2/$3';
$route['recruitment/page/menu/(:any)/(:any)/(:any)'] = 'recruitment/page/menu/$1/$2/$3';
$route['placement'] = 'placement/page/menu';
$route['recruitment'] = 'recruitment/page/menu';
$route['default_controller'] = 'page';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
