<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportAyana;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HumanResource\CompanyController;
use App\Http\Controllers\HumanResource\EmployeeController;
use App\Http\Controllers\HumanResource\BusinessPlantController;
use App\Http\Controllers\HumanResource\TerritoryController;
use App\Http\Controllers\HumanResource\CostCenterController;
use App\Http\Controllers\HumanResource\DepartmentController;
use App\Http\Controllers\HumanResource\DivisionController;
use App\Http\Controllers\HumanResource\JobTitleController;
use App\Http\Controllers\Finance\FormController;
use App\Http\Controllers\Finance\ReportController;
use App\Http\Controllers\Finance\EntertainmentForm;
use App\Http\Controllers\Finance\AddMaterialMaster;
use App\Http\Controllers\Finance\AddBusinessPartner;
use App\Http\Controllers\Finance\PurchaseRequisition;
use App\Http\Controllers\Finance\PurchaseRequisitionMarketList;
use App\Http\Controllers\Finance\PurchaseOrder as PurchaseOrderApproval;
use App\Http\Controllers\Finance\PNL;
use App\Http\Controllers\Finance\CashAdvance;
use App\Http\Controllers\Finance\CashAdvanceGC;
use App\Http\Controllers\Folio;
use App\Http\Controllers\AccessManagement\MasterMenu;
use App\Http\Controllers\AccessManagement\MasterRole;
use App\Http\Controllers\AccessManagement\RoleMenu;
use App\Http\Controllers\AccessManagement\MasterMenuAccess;
use App\Http\Controllers\AccessManagement\MenuAccessRole;
use App\Http\Controllers\AccessManagement\UserPermission;
use App\Http\Controllers\AccessManagement\UserHasRole;
use App\Http\Controllers\AccessManagement\CustomAccess;
use App\Http\Controllers\Dashboards\Menu_Outlet\Menu_Outlet;
use App\Http\Controllers\ZohoController;
use App\Http\Controllers\ZohoAPIController;
use App\Http\Controllers\APIController;
use App\Http\Controllers\SAP\RealEstate;
use App\Http\Controllers\SAP\PurchaseOrder;
use App\Http\Controllers\SAP\CashBalance;
use App\Http\Controllers\SAP\Inventory;
use App\Http\Controllers\SAP\Reservation;
use App\Http\Controllers\SAP\Recipe;
use App\Http\Controllers\SAP\MenuEngineering;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\WebService\Employee as EmployeeTalenta;
use App\Http\Controllers\WebService\ReportEmail;
use App\Http\Controllers\WebService\BudgetActual;
use App\Http\Controllers\WebService\FnbBomCost;
use App\Http\Controllers\WebService\Book4Time;
use App\Http\Controllers\WebService\MaterialLastPrice;
use App\Http\Controllers\WebService\QuinosPOS;
use App\Http\Controllers\WebService\BIExchangeRate;
use App\Http\Controllers\WebService\Zapier;
use App\Http\Controllers\Finance\FuelForm;
use App\Http\Controllers\Finance\ReportMRP;
use App\Http\Controllers\Finance\ReportAging;
use App\Http\Controllers\VCC_Controller;
use App\Http\Controllers\PusherBroadcast;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Notifications;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/* =================================================== GENERAL ============================================== */
// Route::get('/{vue_capture?}', function () {
//     return view('index');
// })->where('vue_capture', '[\/\w\.-]*');
// Route::get('/clear-cache-all', function() {
//     Artisan::call('cache:clear');
//     dd("Cache Clear All");
// });

Route::get('/page/health', function(){
    return response()->json(['message'=>'The page is working normally'], 200);
});

Route::group(['middleware'=>'check_permission', 'permission'=>['view_/dashboard', 'namespace' => 'App\Http\Controllers\HomeController']], function () {
    Route::get('/dashboard', [HomeController::class,'index'])->name('dashboard.summary')->middleware('auth.api');
});
Route::get('/', function(){
    return redirect()->route('dashboard.summary');
});

// Custom form
Route::get('/credit-card-validator', [HomeController::class,'credit_card_validator'])->name('credit-card-validator')->middleware('auth.api');
Route::get('/jotform/capex', [HomeController::class,'jotform'])->name('jotform.capex')->middleware('auth.api');
Route::get('/jotform/table/capex', [HomeController::class,'jotform_table_capex'])->name('jotform.table.capex')->middleware('auth.api');
// End custom form

Route::get('/login', function () {
    return view('pages.auth.login');
})->middleware('ifauth.api');

Route::get('/change-password', function () {
    return view('pages.auth.change_password');
})->middleware('ifauth.api');

Route::get('/generate_contract', [ContractController::class,'index']);
Route::get('/generate_contract/ajaxResort', [ContractController::class,'ajax_resort']);
Route::post('/generate_contract/process', [ContractController::class,'process']);

Route::group(['prefix' => 'auth', 'namespace' => 'App\Http\Controllers\Auth'], function () {
    Route::post('/login', 'AuthController@login');
    Route::any('/logout', 'AuthController@logout')->name('auth.logout');
    Route::post('/act/change-password', [AuthController::class, 'act_change_password'])->name('auth.act_change_password');
    Route::any('/act/forgot-password', 'AuthController@forgot_password')->middleware('auth.api');
});

Route::group(['middleware'=>'auth.api', 'prefix' => 'user', 'namespace' => 'App\Http\Controllers\UserController'], function(){
    Route::group(['permission'=>['view_/dashboard']], function(){
        Route::post('/act/change-password', [UserController::class, 'act_change_password'])->name('user.act_change_password');
        Route::post('/act/change-profile', [UserController::class, 'act_change_profile'])->name('user.act_change_profile');
        Route::get('/change-password', [UserController::class, 'change_password'])->name('user.change_password');
        Route::get('/change-profile', [UserController::class, 'change_profile'])->name('user.change_profile');
    });
});
/* ================================================= END GENERAL ============================================ */

/* =============================================== WITH PERMISSION ========================================== */
Route::group(['middleware'=>'auth.api', 'prefix' => 'human-resource/master-data', 'namespace' => 'App\Http\Controllers\HumanResource\CompanyController'], function(){
    // Group Company
    Route::get('/company', [CompanyController::class, 'index'])->name('human-resource.master-data.company');
    // Permission for create, add or ajax
    Route::group(['permission'=>['create_/human-resource/master-data/company', 'add_/human-resource/master-data/company', 'view_/human-resource/master-data/company']], function(){
        Route::get('/company/getData', [CompanyController::class, 'getData']);
    });
    // Permission for edit
    Route::group(['permission'=>['edit_/human-resource/master-data/company', 'update_/human-resource/master-data/company']], function(){
        Route::get('/company/edit/{id}', [CompanyController::class, 'edit']);
        Route::post('/company/act/edit', [CompanyController::class, 'act_edit']);
    });
    // Permission for delete
    Route::group(['permission'=>['delete_/human-resource/master-data/company']], function(){
        Route::get('/company/delete/{id}', [CompanyController::class, 'act_delete']);
    });
    // End Group Company

    // Group Business Plant
    Route::get('/business-plant', [BusinessPlantController::class, 'index'])->name('human-resource.master-data.business-plant');
    // Permission for create, add or ajax
    Route::group(['permission'=>['create_/human-resource/master-data/business-plant', 'add_/human-resource/master-data/business-plant', 'view_/human-resource/master-data/business-plant']], function(){
        Route::get('/business-plant/create', [BusinessPlantController::class, 'create']);
        Route::post('/business-plant/act/create', [BusinessPlantController::class, 'act_create']);
        Route::get('/business-plant/getData', [BusinessPlantController::class, 'getData']);
        Route::get('/business-plant/getCompany', [BusinessPlantController::class, 'getCompany']);
    });
    // Permission for edit
    Route::group(['permission'=>['edit_/human-resource/master-data/business-plant', 'update_/human-resource/master-data/business-plant']], function(){
        Route::get('/business-plant/edit/{id}', [BusinessPlantController::class, 'edit']);
        Route::post('/business-plant/act/edit', [BusinessPlantController::class, 'act_edit']);
    });
    // Permission for delete
    Route::group(['permission'=>['delete_/human-resource/master-data/business-plant']], function(){
        Route::get('/business-plant/delete/{id}', [BusinessPlantController::class, 'act_delete']);
    });
    // End Group Business Plant

    // Group Territory
    Route::get('/territory', [TerritoryController::class, 'index'])->name('human-resource.master-data.territory');
    // Permission for create, add or ajax
    Route::group(['permission'=>['create_/human-resource/master-data/territory', 'add_/human-resource/master-data/territory', 'view_/human-resource/master-data/territory']], function(){
        Route::get('/territory/getData', [TerritoryController::class, 'getData']);
    });
    // End Group Territory

    // Group Cost Center
    Route::get('/cost-center', [CostCenterController::class, 'index'])->name('human-resource.master-data.cost-center');
    // Permission for create, add or ajax
    Route::group(['permission'=>['create_/human-resource/master-data/cost-center', 'add_/human-resource/master-data/cost-center', 'view_/human-resource/master-data/cost-center']], function(){
        Route::get('/cost-center/getData', [CostCenterController::class, 'getData']);
    });
    // End Group Cost Center

    // Group Department
    Route::get('/department', [DepartmentController::class, 'index'])->name('human-resource.master-data.department');
    // Permission for create, add or ajax
    Route::group(['permission'=>['create_/human-resource/master-data/department', 'add_/human-resource/master-data/department', 'view_/human-resource/master-data/department']], function(){
        Route::get('/department/getData', [DepartmentController::class, 'getData']);
    });
    // End Group Department

    // Group Division
    Route::get('/division', [DivisionController::class, 'index'])->name('human-resource.master-data.division');
    // Permission for create, add or ajax
    Route::group(['permission'=>['create_/human-resource/master-data/division', 'add_/human-resource/master-data/division', 'view_/human-resource/master-data/division']], function(){
        Route::get('/division/getData', [DivisionController::class, 'getData']);
    });
    // End Group Division

    // Group Job Title
    Route::get('/job-title', [JobTitleController::class, 'index'])->name('human-resource.master-data.job-title');
    // Permission for create, add or ajax
    Route::group(['permission'=>['create_/human-resource/master-data/job-title', 'add_/human-resource/master-data/job-title', 'view_/human-resource/master-data/job-title']], function(){
        Route::get('/job-title/getData', [JobTitleController::class, 'getData']);
    });
    // End Group Job Title
});

Route::group(['middleware'=>'auth.api','prefix' => 'human-resource/employee-list', 'namespace' => 'App\Http\Controllers\HumanResource\EmployeeController'], function(){

    Route::get('/employee', [EmployeeController::class, 'employeeSearch'])->name('human-resource.employee.search');
    Route::group(['permission'=>['create_/human-resource/employee-list/employee', 'add_/human-resource/master-data/employee', 'view_/human-resource/master-data/employee']], function(){
        Route::get('/', function(){
            return redirect()->route('human-resource.employee.search');
        });
        Route::get('/employee/filter', [EmployeeController::class, 'index'])->name('human-resource.employee.list');
        Route::post('/employee/search/data', [EmployeeController::class, 'employeeSearchData']);

        Route::get('/employee/filter/getData', [EmployeeController::class, 'employeeGetData']);
        Route::get('/employee/view/{id}/{sap_cost_center}', [EmployeeController::class, 'employeeEdit']);

        Route::get('/employee/filter/getPlant', [EmployeeController::class, 'employeeFilterGetPlant']);
        Route::get('/employee/filter/getTerritory', [EmployeeController::class, 'employeeFilterGetTerritory']);
        Route::get('/employee/filter/getEmployee', [EmployeeController::class, 'employeeFilterGetEmployee']);
    });
});

Route::group(['middleware'=>'auth.api', 'prefix' => 'report', 'namespace' => 'App\Http\Controllers\ReportAyana'], function(){
	// SANDBOX REPORT
	// Route::get('/', [ReportAyana::class, 'index'])->name('revenue_report.welcome');
	// Route::any('/report_revenue', [ReportAyana::class, 'index'])->name('revenue_report.all');
	// Route::get('/report_revenue/{quartal}', [ReportAyana::class, 'index'])->name('revenue_report.quartal');
    // Route::any('/report_revenue_inline', [ReportAyana::class, 'report_inline'])->name('revenue_report_inline.all');
    // Route::get('/report_revenue_inline/{quartal}', [ReportAyana::class, 'report_inline'])->name('revenue_report_inline.quartal');
    Route::get('/fnb_delonix', [ReportAyana::class, 'revenue_daily_fb_delonix']);
    Route::group(['permission'=>['create_/report/fnb_delonix', 'add_/report/fnb_delonix', 'view_/report/fnb_delonix']], function(){
        Route::get('/fnb_delonix/{id}', [ReportAyana::class, 'revenue_daily_fb_delonix_detail']);
    });

    Route::get('/room_villa_revenue_daily', [ReportAyana::class, 'report_room_villa_daily']);
    Route::get('/revenue_daily', [ReportAyana::class, 'report_revenue_daily']);

    Route::group(['middleware'=>'auth.api', 'prefix' => 'ajax', 'namespace' => 'App\Http\Controllers\ReportAyana'], function(){
        Route::post('/getSubResort', [ReportAyana::class, 'ajax_getSubResort']);
    });

    Route::get('/cancellation_daily', [ReportAyana::class, 'report_cancellation_daily']);
    Route::get('/cancellation_daily/filter_cancellation_daily', [ReportAyana::class, 'filter_report_cancellation_daily']);

    Route::get('/menu_sales_revenue', [ReportAyana::class, 'report_menu_total']);

    Route::get('/fnb_ayana', [ReportAyana::class, 'revenue_daily_fb_ayana']);
    Route::get('/fnb_ayana/filter_pos_daily', [ReportAyana::class, 'filter_daily_fb_ayana']);
    // permission untuk sub menu yang bila di klik dengan link dari menu utama
    Route::group(['permission'=>['view_/report/fnb_ayana', 'create_/report/fnb_ayana']], function(){
        Route::get('/fnb_ayana_outlet', [ReportAyana::class, 'revenue_daily_fb_ayana_outlet'])->name('ayana.pos_revenue.outlet');
        Route::get('/fnb_ayana_outlet/ytd', [ReportAyana::class, 'revenue_daily_fb_ayana_outlet_ytd'])->name('ayana.pos_revenue.outlet.ytd');
        Route::get('/fnb_ayana_outlet_detail', [ReportAyana::class, 'revenue_daily_fb_ayana_outlet_detail'])->name('ayana.pos_revenue.outlet_detail');
    });

    Route::get('/sales_daily', [ReportAyana::class, 'report_sales_daily']);
    Route::group(['permission'=>['view_/report/sales_daily', 'create_/report/sales_daily', 'add_/report/sales_daily']], function(){
        Route::get('/sales_daily_outlet', [ReportAyana::class, 'report_sales_daily_outlet'])->name('ReportAyana.sales_daily_outlet');
        Route::get('/sales_daily_outlet_mtd', [ReportAyana::class, 'report_sales_daily_outlet_mtd'])->name('ReportAyana.sales_daily_outlet_mtd');
        Route::get('/get_sales_daily', [ReportAyana::class, 'get_sales_daily'])->name('ReportAyana.get_sales_daily');
        Route::get('/sales_daily_detail', [ReportAyana::class,'report_sales_daily_detail'])->name('ReportAyana.report_sales_daily_detail');
    });

    Route::get('/list_menu_delonix', [ReportAyana::class, 'list_menu_delonix']);
});


Route::group(['middleware'=>'auth.api', 'prefix' => 'folio', 'namespace' => 'App\Http\Controllers\Folio'], function(){
    // FOLIO
    Route::get('/', [Folio::class, 'index'])->name('folio.index');
    Route::group(['permission'=>['view_/folio', 'create_/folio', 'add_/folio']], function(){
        Route::get('/folio_categorized', [Folio::class, 'categorized_folio'])->name('folio.categorized');
        // GET DATA FOLIO AJAX
        Route::get('/get_data', [Folio::class, 'GetFolioAyana'])->name('folio.get_local_data');
        Route::get('/get_data_categorized', [Folio::class, 'GetFolioAyanaCategorized'])->name('folio.get_local_data.categorized');
        // WHE
        Route::get('/folio_detail', [Folio::class, 'GetReferenceFolio'])->name('folio.folio_detail');
        Route::post('/folio_detail_get_data', [Folio::class, 'GetReferenceFolioData'])->name('folio.folio_detail_post');

        Route::get('/get_invoice', [Folio::class, 'GetFolioDetail'])->name('folio.invoice');
        Route::get('/get_invoice_new', [Folio::class, 'GetFolioDetailNew'])->name('folio.invoice_new');
    });

    // Route::get('/invoice_payment_template/data/print', [Folio::class, 'invoice_payment_template_print'])->name('invoice_payment.print');
    Route::get('/invoice_payment/data/download', [Folio::class, 'invoice_payment_template_download'])->name('invoice_payment.download');
});

Route::group(['middleware'=>'auth.api', 'prefix' => 'finance/cash-advance', 'namespace' => 'App\Http\Controllers\Finance\FormController'], function(){
    Route::group(['permission'=>['view_/finance/cash-advance/request']], function(){
        Route::get('/request', [CashAdvance::class, 'request'])->name('finance.form.cash-advance');
        Route::post('/request/getData', [CashAdvance::class, 'request_getData'])->name('finance.form.cash-advance-getData');
        Route::post('/request/save', [CashAdvance::class, 'save'])->name('finance.cash-advance.save');

        Route::get('/approval', [CashAdvance::class, 'approval'])->name('finance.cash-advance.approval');
        Route::post('/approval/getData', [CashAdvance::class, 'approval_getData'])->name('finance.cash-advance.approval.getData');
        Route::post('/approval/getDataExtended', [CashAdvance::class, 'approval_getDataExtended'])->name('finance.cash-advance.approval.getDataExtended');
        Route::post('/approval/submitApprovalForm', [CashAdvance::class, 'submitApprovalForm'])->name('finance.cash-advance.approval.submitApprovalForm');
        Route::post('/approval/save-with-form-data', [CashAdvance::class, 'save_with_form_data'])->name('finance.cash-advance.approval.save_with_form_data');

        Route::get('/report', [CashAdvance::class, 'report'])->name('finance.cash-advance.report');
        Route::post('/report/getData', [CashAdvance::class, 'report_getData'])->name('finance.cash-advance.report.getData');

        Route::post('/getHistoryApproval', [CashAdvance::class, 'getHistoryApproval'])->name('finance.cash-advance.request.getHistoryApproval');
        Route::post('/getHistoryApprovalExtended', [CashAdvance::class, 'getHistoryApprovalExtended'])->name('finance.cash-advance.request.getHistoryApprovalExtended');

        Route::get('/modal-detail', [CashAdvance::class, 'modal_detail'])->name('finance.cash-advance.modal_detail');
        Route::get('/modal-approve-detail', [CashAdvance::class, 'modal_approve_detail'])->name('finance.cash-advance.modal_approve_detail');

        Route::get('/insert-cash-advance', [CashAdvance::class, 'insert_ca_sap'])->name('finance.cash-advance.insert-cash-advance');
        Route::get('/post-cash-advance', [CashAdvance::class, 'post_ca_sap'])->name('finance.cash-advance.post-cash-advance');

        Route::get('/fetch-cash-journal', [CashAdvance::class, 'cajo_list'])->name('finance.cash-advance.fetch-cash-journal');
        Route::get('/fetch-business-transaction', [CashAdvance::class, 'business_trans_list'])->name('finance.cash-advance.fetch-business-transaction');
    });

});

Route::group(['middleware'=>'auth.api', 'prefix' => 'finance/cash-advance-gc', 'namespace' => 'App\Http\Controllers\Finance\FormController'], function(){
    Route::group(['permission'=>['view_/finance/cash-advance/request']], function(){
        Route::get('/all', [CashAdvanceGC::class, 'all'])->name('finance.form.cash-advance-gc.all');
        Route::post('/all/getData', [CashAdvanceGC::class, 'all_getData'])->name('finance.form.cash-advance-gc.all-getData');
        Route::get('/modal-detail', [CashAdvanceGC::class, 'modal_detail'])->name('finance.cash-advance-gc.modal_detail');
        Route::get('/modal-settlement', [CashAdvanceGC::class, 'modal_settlement'])->name('finance.cash-advance-gc.modal_settlement');
        Route::get('/modal-backup', [CashAdvanceGC::class, 'modal_backup'])->name('finance.cash-advance-gc.modal_backup');
        Route::post('/save-adjustment-first', [CashAdvanceGC::class, 'save_adjustment_first'])->name('finance.form.cash-advance-gc.save-adjustment-first');
        Route::post('/save-adjustment-final', [CashAdvanceGC::class, 'save_adjustment_final'])->name('finance.form.cash-advance-gc.save-adjustment-final');
        Route::post('/delete-parked-ca', [CashAdvanceGC::class, 'delete_parked_ca'])->name('finance.form.cash-advance-gc.delete-parked-ca');
        Route::post('/getHistoryApprovalExtended', [CashAdvanceGC::class, 'getHistoryApprovalExtended'])->name('finance.cash-advance-gc.request.getHistoryApprovalExtended');

        Route::get('/add-reimbursement', [CashAdvanceGC::class, 'add_reimbursement'])->name('finance.form.cash-advance-gc.add-reimbursement');
        Route::post('/add-reimbursement/getData', [CashAdvanceGC::class, 'add_reimbursement_getData'])->name('finance.form.cash-advance-gc.add-reimbursement-getData');
        Route::post('/add-reimbursement/add-reimbursement-batch', [CashAdvanceGC::class, 'add_reimbursement_batch'])->name('finance.form.cash-advance-gc.add-reimbursement-batch');

        Route::get('/list-reimbursement', [CashAdvanceGC::class, 'list_reimbursement'])->name('finance.form.cash-advance-gc.list-reimbursement');
        Route::post('/list-reimbursement/getData', [CashAdvanceGC::class, 'list_reimbursement_getData'])->name('finance.form.cash-advance-gc.list-reimbursement-getData');
        Route::get('/modal-detail-list-reimbursement', [CashAdvanceGC::class, 'modal_detail_list_reimbursement'])->name('finance.cash-advance-gc.modal_detail_list_reimbursement');
        Route::get('/modal-detail-ca-reimbursement', [CashAdvanceGC::class, 'modal_detail_ca_reimbursement'])->name('finance.cash-advance-gc.modaldetail_ca_reimbursement');
        Route::post('/update-reimbursement', [CashAdvanceGC::class, 'update_reimbursement'])->name('finance.form.cash-advance-gc.update-reimbursement');
        Route::post('/pay-reimbursement', [CashAdvanceGC::class, 'pay_reimbursement'])->name('finance.form.cash-advance-gc.pay-reimbursement');
        Route::post('/cancel-reimbursement', [CashAdvanceGC::class, 'cancel_reimbursement'])->name('finance.form.cash-advance-gc.cancel-reimbursement');
    });

});

Route::group(['middleware'=>'auth.api','prefix' => 'finance/report', 'namespace' => 'App\Http\Controllers\Finance\ReportController'], function(){
    Route::get('/cash-advance', [ReportController::class, 'cash_advance'])->name('finance.report.cash-advance');
    Route::get('/refund', [ReportController::class, 'refund'])->name('finance.report.refund');
});

Route::group(['middleware'=>'auth.api','prefix' => 'finance/entertainmentForm', 'namespace' => 'App\Http\Controllers\Finance\EntertainmentForm'], function(){
    Route::get('/request', [EntertainmentForm::class, 'entertainmentform_request'])->name('finance.entertainmentform.request');
    Route::post('/request/getData', [EntertainmentForm::class, 'entertainmentform_request_getData'])->name('finance.entertainmentform.request.getData');
    Route::post('/request/getHistoryApproval', [EntertainmentForm::class, 'getHistoryApproval'])->name('finance.entertainmentform.request.getHistoryApproval');

    Route::get('/request/modalRequest', [EntertainmentForm::class, 'modal_request'])->name('finance.entertainmentform.modal_request');
    Route::get('/request/ajaxLocation', [EntertainmentForm::class, 'ajax_location'])->name('finance.entertainmentform.ajax_location');
    Route::get('/request/ajaxSBU', [EntertainmentForm::class, 'ajax_sbu'])->name('finance.entertainmentform.ajax_sbu');
    Route::get('/request/ajaxEntType', [EntertainmentForm::class, 'ajax_enttype'])->name('finance.entertainmentform.ajax_enttype');
    Route::post('/request/save', [EntertainmentForm::class, 'save'])->name('finance.entertainmentform.save');

    Route::get('/approval', [EntertainmentForm::class, 'entertainmentform_approval'])->name('finance.entertainmentform.approval');
    Route::post('/approval/getData', [EntertainmentForm::class, 'entertainmentform_approval_getData'])->name('finance.entertainmentform.approval.getData');

    Route::get('/report', [EntertainmentForm::class, 'entertainmentform_report'])->name('finance.entertainmentform.report');

    Route::get('/modal-detail', [EntertainmentForm::class, 'modal_detail'])->name('finance.entertainmentform.modal_detail');

    Route::post('/approval/submitApprovalForm', [EntertainmentForm::class, 'submitApprovalForm'])->name('finance.entertainmentform.approval.submitApprovalForm');
});

Route::group(['middleware'=>'auth.api','prefix' => 'finance/add-material-master', 'namespace' => 'App\Http\Controllers\Finance\AddMaterialMaster'], function(){
    Route::get('/request', [AddMaterialMaster::class, 'request'])->name('finance.material-master.request');
    Route::post('/request/getData', [AddMaterialMaster::class, 'request_getData'])->name('finance.material-master.request.getData');
    Route::post('/request/save', [AddMaterialMaster::class, 'save'])->name('finance.material-master.save');

    Route::get('/approval', [AddMaterialMaster::class, 'approval'])->name('finance.material-master.approval');
    Route::post('/approval/getData', [AddMaterialMaster::class, 'approval_getData'])->name('finance.material-master.approval.getData');
    Route::post('/approval/submitApprovalForm', [AddMaterialMaster::class, 'submitApprovalForm'])->name('finance.material-master.approval.submitApprovalForm');
    Route::post('/approval/save-with-form-data', [AddMaterialMaster::class, 'save_with_form_data'])->name('finance.material-master.approval.save_with_form_data');

    Route::get('/report', [AddMaterialMaster::class, 'report'])->name('finance.material-master.report');
    Route::post('/report/getData', [AddMaterialMaster::class, 'report_getData'])->name('finance.material-master.report.getData');

    Route::post('/getHistoryApproval', [AddMaterialMaster::class, 'getHistoryApproval'])->name('finance.material-master.request.getHistoryApproval');

    Route::get('/modal-detail', [AddMaterialMaster::class, 'modal_detail'])->name('finance.material-master.modal_detail');
    Route::get('/modal-approve-detail', [AddMaterialMaster::class, 'modal_approve_detail'])->name('finance.material-master.modal_approve_detail');

    Route::post('/ajax/getCostCenterCustom', [AddMaterialMaster::class, 'ajax_getCostCenterCustom'])->name('finance.material-master.ajax_getCostCenterCustom');
    Route::post('/ajax/getMidjobCustom', [AddMaterialMaster::class, 'ajax_getMidjobCustom'])->name('finance.material-master.ajax_getMidjobCustom');

});

Route::group(['middleware'=>'auth.api','prefix' => 'finance/add-business-partner', 'namespace' => 'App\Http\Controllers\Finance\AddBusinessPartner'], function(){
    Route::get('/request', [AddBusinessPartner::class, 'request'])->name('finance.add-business-partner.request');
    Route::post('/request/getData', [AddBusinessPartner::class, 'request_getData'])->name('finance.add-business-partner.request.getData');
    Route::post('/request/save', [AddBusinessPartner::class, 'save'])->name('finance.add-business-partner.save');

    Route::get('/approval', [AddBusinessPartner::class, 'approval'])->name('finance.add-business-partner.approval');
    Route::post('/approval/getData', [AddBusinessPartner::class, 'approval_getData'])->name('finance.add-business-partner.approval.getData');
    Route::post('/approval/submitApprovalForm', [AddBusinessPartner::class, 'submitApprovalForm'])->name('finance.add-business-partner.approval.submitApprovalForm');
    Route::post('/approval/save-with-form-data', [AddBusinessPartner::class, 'save_with_form_data'])->name('finance.add-business-partner.approval.save_with_form_data');

    Route::get('/report', [AddBusinessPartner::class, 'report'])->name('finance.add-business-partner.report');
    Route::post('/report/getData', [AddBusinessPartner::class, 'report_getData'])->name('finance.add-business-partner.report.getData');

    Route::post('/getHistoryApproval', [AddBusinessPartner::class, 'getHistoryApproval'])->name('finance.add-business-partner.request.getHistoryApproval');

    Route::get('/modal-detail', [AddBusinessPartner::class, 'modal_detail'])->name('finance.add-business-partner.modal_detail');
    Route::get('/modal-approve-detail', [AddBusinessPartner::class, 'modal_approve_detail'])->name('finance.add-business-partner.modal_approve_detail');

    Route::post('/ajax/getCostCenterCustom', [AddBusinessPartner::class, 'ajax_getCostCenterCustom'])->name('finance.add-business-partner.ajax_getCostCenterCustom');
    Route::post('/ajax/getMidjobCustom', [AddBusinessPartner::class, 'ajax_getMidjobCustom'])->name('finance.add-business-partner.ajax_getMidjobCustom');
});

Route::group(['middleware'=>'auth.api','prefix' => 'finance/add-reservation', 'namespace' => 'App\Http\Controllers\SAP\Reservation'], function(){
    Route::get('/request', [Reservation::class, 'request'])->name('finance.add-reservation.request');
    Route::post('/request/getData', [Reservation::class, 'request_getData'])->name('finance.add-reservation.request.getData');
    Route::get('/modal-detail', [Reservation::class, 'modal_detail'])->name('finance.add-reservation.modal_detail');

    Route::get('/approval', [Reservation::class, 'approval'])->name('finance.add-reservation.approval');
    Route::post('/approval/getData', [Reservation::class, 'approval_getData'])->name('finance.add-reservation.approval.getData');

    Route::get('/report', [Reservation::class, 'report'])->name('finance.add-reservation.report');
    Route::post('/report/getData', [Reservation::class, 'report_getData'])->name('finance.add-reservation.report.getData');

    Route::post('/getHistoryApproval', [Reservation::class, 'getHistoryApproval'])->name('finance.add-reservation.request.getHistoryApproval');
    Route::post('/request/save', [Reservation::class, 'save'])->name('finance.add-reservation.save');
    Route::post('/request/update', [Reservation::class, 'update'])->name('finance.add-reservation.update');

    Route::post('/approval/submitApprovalForm', [Reservation::class, 'submitApprovalForm'])->name('finance.add-reservation.approval.submitApprovalForm');
    Route::get('/list', [Reservation::class, 'list_reservation'])->name('finance.add_reservation.list_reservation');
});

Route::group(['middleware'=>'auth.api','prefix' => 'finance/purchase-requisition', 'namespace' => 'App\Http\Controllers\Finance\PurchaseRequisition'], function(){
    Route::get('/request', [PurchaseRequisition::class, 'request'])->name('finance.purchase-requisition.request');

    Route::group(['permission'=>['view_/finance/purchase-requisition/request']], function(){
        Route::post('/request/getData', [PurchaseRequisition::class, 'request_getData'])->name('finance.purchase-requisition.request.getData');
        Route::post('/request/save', [PurchaseRequisition::class, 'save'])->name('finance.purchase-requisition.save');
        Route::get('/search-asset', [PurchaseRequisition::class, 'search_asset'])->name('finance.purchase-requisition.search_asset  ');
        Route::get('/search-vendor', [PurchaseRequisition::class, 'search_vendor'])->name('finance.purchase-requisition.search_vendor');
        Route::get('/search-material', [PurchaseRequisition::class, 'search_material'])->name('finance.purchase-requisition.search_material');
        Route::post('/getHistoryApproval', [PurchaseRequisition::class, 'getHistoryApproval'])->name('finance.purchase-requisition.request.getHistoryApproval');

        Route::post('/save_comment', [PurchaseRequisition::class, 'save_comment'])->name('finance.purchase-requisition.save_comment');
        Route::post('/approve', [PurchaseRequisition::class, 'approve'])->name('finance.purchase-requisition.approve');
        Route::post('/reject', [PurchaseRequisition::class, 'reject'])->name('finance.purchase-requisition.reject');
        Route::post('/cancel', [PurchaseRequisition::class, 'cancel'])->name('finance.purchase-requisition.cancel');
        Route::post('/update_attachment', [PurchaseRequisition::class, 'update_attachment'])->name('finance.purchase-requisition.update_attachment');
        Route::post('/update_comment', [PurchaseRequisition::class, 'update_comment'])->name('finance.purchase-requisition.update_comment');
        Route::post('/delete_comment', [PurchaseRequisition::class, 'delete_comment'])->name('finance.purchase-requisition.delete_comment');

        Route::get('/modal-detail', [PurchaseRequisition::class, 'modal_detail'])->name('finance.purchase-requisition.modal_detail');
        Route::get('/detail', [PurchaseRequisition::class, 'detail'])->name('finance.purchase-requisition.detail_modal');
        Route::get('/detail/{id}', [PurchaseRequisition::class, 'detail'])->name('finance.purchase-requisition.detail');
        Route::get('/modal-approve-detail', [PurchaseRequisition::class, 'modal_approve_detail'])->name('finance.purchase-requisition.modal_approve_detail');

        Route::get('/modal-detail-po', [PurchaseRequisition::class, 'modal_detail_po'])->name('finance.purchase-requisition.modal_detail_po');

        Route::post('/ajax_sloc', [PurchaseRequisition::class, 'ajax_sloc'])->name('finance.purchase-requisition.ajax_sloc');
        Route::post('/ajax_costcenter', [PurchaseRequisition::class, 'ajax_costcenter'])->name('finance.purchase-requisition.ajax_costcenter');
    });

    Route::get('/approval', [PurchaseRequisition::class, 'approval'])->name('finance.purchase-requisition.approval');

    Route::group(['permission'=>['view_/finance/purchase-requisition/request']], function(){
        Route::post('/approval/getData', [PurchaseRequisition::class, 'approval_getData'])->name('finance.purchase-requisition.approval.getData');
        Route::post('/approval/submitApprovalForm', [PurchaseRequisition::class, 'submitApprovalForm'])->name('finance.purchase-requisition.approval.submitApprovalForm');
    });


    Route::get('/report', [PurchaseRequisition::class, 'report'])->name('finance.purchase-requisition.report');
    Route::get('/list', [PurchaseRequisition::class, 'list_pr'])->name('finance.purchase-requisition.list_pr');
});

Route::group(['middleware'=>'auth.api','prefix' => 'finance/purchase-requisition-marketlist', 'namespace' => 'App\Http\Controllers\Finance\PurchaseRequisitionMarketList'], function(){
    Route::get('/request', [PurchaseRequisitionMarketList::class, 'request'])->name('finance.purchase-requisition-marketlist.request');
    Route::get('/request/print', [PurchaseRequisitionMarketList::class, 'print'])->name('finance.purchase-requisition-marketlist.request.print');
    Route::group(['permission'=>['view_/finance/purchase-requisition-marketlist/request']], function(){
        Route::any('/request/getData', [PurchaseRequisitionMarketList::class, 'request_getData'])->name('finance.purchase-requisition-marketlist.request.getData');
        Route::post('/request/save', [PurchaseRequisitionMarketList::class, 'save'])->name('finance.purchase-requisition-marketlist.save');

        Route::get('/modal-detail', [PurchaseRequisitionMarketList::class, 'modal_detail'])->name('finance.purchase-requisition-marketlist.modal_detail');
        Route::post('/request/update', [PurchaseRequisitionMarketList::class, 'update'])->name('finance.purchase-requisition-marketlist.update');
    });

    Route::get('/approval', [PurchaseRequisitionMarketList::class, 'approval'])->name('finance.purchase-requisition-marketlist.approval');
    Route::group(['permission'=>['view_/finance/purchase-requisition-marketlist/request']], function(){
        Route::any('/approval/getData', [PurchaseRequisitionMarketList::class, 'approval_getData'])->name('finance.purchase-requisition-marketlist.approval.getData');
        Route::post('/approval/submitApprovalForm', [PurchaseRequisitionMarketList::class, 'submitApprovalForm'])->name('finance.purchase-requisition-marketlist.approval.submitApprovalForm');
        Route::post('/approval/submitApprovalFormBatch', [PurchaseRequisitionMarketList::class, 'submitApprovalFormBatch'])->name('finance.purchase-requisition-marketlist.approval.submitApprovalFormBatch');
    });
    Route::get('/report', [PurchaseRequisitionMarketList::class, 'report'])->name('finance.purchase-requisition-marketlist.report');
    Route::post('/report/getData', [PurchaseRequisitionMarketList::class, 'report_getData'])->name('finance.purchase-requisition-marketlist.report.getData');
    Route::get('/list', [PurchaseRequisitionMarketList::class, 'list_ml'])->name('finance.purchase-requisition-marketlist.list_ml');

    Route::get('/master-template', [PurchaseRequisitionMarketList::class, 'list_template'])->name('marketlisttemplate.list');
    Route::group(['permission'=>['create_/finance/purchase-requisition-marketlist/master-template', 'add_/finance/purchase-requisition-marketlist/master-template', 'view_/finance/purchase-requisition-marketlist/master-template']], function(){
        Route::get('/master-template/add', [PurchaseRequisitionMarketList::class, 'add_template'])->name('marketlisttemplate.add');
        Route::post('/master-template/create', [PurchaseRequisitionMarketList::class, 'create_template'])->name('marketlisttemplate.create');
        Route::get('/master-template/getData', [PurchaseRequisitionMarketList::class, 'getData']);
    });

    Route::group(['permission'=>['update_/finance/purchase-requisition-marketlist/master-template']], function(){
        Route::any('/master-template/edit/{plant?}/{template?}', [PurchaseRequisitionMarketList::class, 'edit_template'])->name('marketlisttemplate.update');
    });
});

Route::group(['middleware'=>'auth.api','prefix' => 'finance/purchase-order', 'namespace' => 'App\Http\Controllers\Finance\PurchaseOrder'], function(){
    Route::get('/approval', [PurchaseOrderApproval::class, 'list'])->name('finance.purchase-order.list');
    Route::group(['permission'=>['view_/finance/purchase-order/approval']], function(){
        Route::get('/', function(){
            return redirect()->route('finance.purchase-order.list');
        });
        Route::get('/detail', [PurchaseOrderApproval::class, 'detail'])->name('finance.purchase-order.detail_modal');
        Route::get('/detail/{id}', [PurchaseOrderApproval::class, 'detail'])->name('finance.purchase-order.detail');
        Route::get('/detail_approved/{id}', [PurchaseOrderApproval::class, 'detail_approved'])->name('finance.purchase-order.detail_approved');
        Route::post('/approval/submitApprovalPO', [PurchaseOrderApproval::class, 'submitApprovalPO'])->name('finance.purchase-order.approval.submit');
        Route::post('/approve', [PurchaseOrderApproval::class, 'approve'])->name('finance.purchase-order.approve');

        Route::post('/export', [PurchaseOrderApproval::class, 'export'])->name('finance.purchase-order.export');
    });
    Route::get('/report', [PurchaseOrderApproval::class, 'report'])->name('finance.purchase-order.report');
    Route::get('/list', [PurchaseOrderApproval::class, 'list_po'])->name('finance.purchase-order.list_po');
});

Route::group(['middleware'=>'auth.api','prefix' => 'finance/fuel', 'namespace' => 'App\Http\Controllers\Finance\FuelForm'], function(){
    Route::get('/request', [FuelForm::class, 'request'])->name('finance.fuel.request');
});

Route::group(['middleware'=>'auth.api', 'prefix' => 'access/management', 'namespace' => 'App\Http\Controllers\Finance\ReportController'], function(){
    // Master Menu
    Route::get('/master-menu', [MasterMenu::class, 'index'])->name('mastermenu.list');
    Route::group(['permission'=>['create_/access/management/master-menu', 'add_/access/management/master-menu', 'view_/access/management/master-menu']], function(){
        Route::get('/master-menu/view/{id}', [MasterMenu::class, 'view_menu'])->name('mastermenu.view');
        Route::get('/master-menu/add', [MasterMenu::class, 'add_menu'])->name('mastermenu.add');
        Route::post('/master-menu/create', [MasterMenu::class, 'create'])->name('mastermenu.create');
        Route::get('/master-menu/getData', [MasterMenu::class, 'getData']);
    });
    Route::group(['permission'=>['delete_/access/management/master-menu']], function(){
        Route::get('/master-menu/delete/{id}', [MasterMenu::class, 'delete'])->name('mastermenu.delete');
        Route::get('/master-menu/remove/{id}', [MasterMenu::class, 'remove'])->name('mastermenu.remove');

    });
    Route::group(['permission'=>['update_/access/management/master-menu']], function(){
        Route::any('/master-menu/edit/{id}', [MasterMenu::class, 'update'])->name('mastermenu.update');
        Route::post('/master-menu/sort', [MasterMenu::class, 'sort'])->name('mastermenu.list.sort')->middleware('check_permission');
    });

    // Master Role
    Route::get('/master-role', [MasterRole::class, 'index'])->name('masterrole.list');
    Route::group(['permission'=>['create_/access/management/master-role', 'add_/access/management/master-role', 'view_/access/management/master-role']], function(){
        Route::get('/master-role/add', [MasterRole::class, 'add_role'])->name('masterrole.add');
        Route::post('/master-role/create', [MasterRole::class, 'create'])->name('masterrole.create');
        Route::get('/master-role/getData', [MasterRole::class, 'getData']);
    });
    Route::group(['permission'=>['delete_/access/management/master-role']], function(){
        Route::get('/master-role/delete/{id}', [MasterRole::class, 'delete'])->name('masterrole.delete');
        Route::get('/master-role/remove/{id}', [MasterRole::class, 'remove'])->name('masterrole.remove');
    });
    Route::group(['permission'=>['update_/access/management/master-role']], function(){
        Route::any('/master-role/edit/{id}', [MasterRole::class, 'update'])->name('masterrole.update');
    });

    // Master Menu Access
    Route::get('/master-access', [MasterMenuAccess::class, 'index'])->name('masteraccess.list');
    Route::group(['permission'=>['create_/access/management/master-access', 'add_/access/management/master-role', 'view_/access/management/master-role']], function(){
        Route::get('/master-access/add', [MasterMenuAccess::class, 'add_access'])->name('masteraccess.add');
        Route::post('/master-access/create', [MasterMenuAccess::class, 'create'])->name('masteraccess.create');
        Route::get('/master-access/getData', [MasterMenuAccess::class, 'getData']);
    });
    Route::group(['permission'=>['delete_/access/management/master-access']], function(){
        Route::get('/master-access/delete/{id}', [MasterMenuAccess::class, 'delete'])->name('masteraccess.delete');
    });
    Route::group(['permission'=>['update_/access/management/master-access']], function(){
        Route::any('/master-access/edit/{id}', [MasterMenuAccess::class, 'update'])->name('masteraccess.update');
    });

    // Role Menu
    Route::get('/master-rolemenu', [RoleMenu::class, 'index'])->name('masterrolemenu.list');
    Route::group(['permission'=>['create_/access/management/master-rolemenu', 'add_/access/management/master-rolemenu', 'view_/access/management/master-rolemenu']], function(){
        // Route::get('/master-rolemenu/add', [RoleMenu::class, 'add_rolemenu'])->name('masterrolemenu.add');
        Route::get('/master-rolemenu/add', [RoleMenu::class, 'add_rolemenu_permission'])->name('masterrolemenu.add');
        // Route::post('/master-rolemenu/create', [RoleMenu::class, 'create'])->name('masterrolemenu.create');
        Route::post('/master-rolemenu/create', [RoleMenu::class, 'create_rolemenu_permission'])->name('masterrolemenu.create');
        Route::get('/master-rolemenu/getData', [RoleMenu::class, 'getData']);
    });
    Route::group(['permission'=>['delete_/access/management/master-access']], function(){
        Route::any('/master-rolemenu/delete/{role_id}', [RoleMenu::class, 'delete'])->name('masterrolemenu.delete');
        Route::any('/master-rolemenu/remove/{role_id}', [RoleMenu::class, 'remove'])->name('masterrolemenu.remove');
    });
    Route::group(['permission'=>['update_/access/management/master-access']], function(){
        // Route::any('/master-rolemenu/edit/{role_id}', [RoleMenu::class, 'update'])->name('masterrolemenu.update');
        Route::any('/master-rolemenu/edit/{role_id}', [RoleMenu::class, 'update_rolemenu_permission'])->name('masterrolemenu.update');
    });

    // MenuAccess Role
    Route::get('/master-accessrole', [MenuAccessRole::class, 'index'])->name('masteraccessrole.list');
    Route::group(['permission'=>['create_/access/management/master-accessrole', 'add_/access/management/master-accessrole', 'view_/access/management/master-accessrole']], function(){
        Route::any('/master-accessrole/add', [MenuAccessRole::class, 'add_accessrole'])->name('masteraccessrole.add')->middleware('check_permission');
        Route::post('/master-accessrole/create', [MenuAccessRole::class, 'create'])->name('masteraccessrole.create')->middleware('check_permission');
        Route::get('/master-accessrole/getData', [MenuAccessRole::class, 'getData']);
    });
    Route::group(['permission'=>['delete_/access/management/master-accessrole']], function(){
        Route::any('/master-accessrole/delete/{menu_access_id}/{role_id}', [MenuAccessRole::class, 'delete'])->name('masteraccessrole.delete')->middleware('check_permission');
        Route::any('/master-accessrole/remove/{menu_access_id}/{role_id}', [MenuAccessRole::class, 'remove'])->name('masteraccessrole.remove')->middleware('check_permission');
    });
    Route::group(['permission'=>['update_/access/management/master-accessrole']], function(){
        Route::any('/master-accessrole/edit/{menu_access_id}/{role_id}', [MenuAccessRole::class, 'update'])->name('masteraccessrole.update')->middleware('check_permission');

    });

    // User Has Role (list all role attached to user)
    Route::get('/user-has-role', [UserHasRole::class, 'index'])->name('userhasrole.list');
    Route::group(['permission'=>['create_/access/management/user-has-role', 'add_/access/management/user-has-role', 'view_/access/management/user-has-role']], function(){
        Route::get('/user-has-role/getData', [UserHasRole::class, 'getData']);
    });

    // Custom Access Plant Employee
    Route::get('/custom-plant-access', [CustomAccess::class, 'index'])->name('custom-plant-access.list');
    Route::group(['permission'=>['create_/access/management/custom-plant-access', 'add_/access/management/custom-plant-access']], function(){
        Route::get('/custom-plant-access/add', [CustomAccess::class, 'add'])->name('custom-plant-access.add');
        Route::post('/custom-plant-access/create', [CustomAccess::class, 'create'])->name('custom-plant-access.create');
        Route::get('/custom-plant-access/getData', [CustomAccess::class, 'getData']);
    });

    Route::group(['permission'=>['delete_/access/management/custom-plant-access']], function(){
        Route::any('/custom-plant-access/delete/{employee_id}/{menu_id}', [CustomAccess::class, 'delete'])->name('custom-plant-access.delete');
    });

    Route::group(['permission'=>['update_/access/management/custom-plant-access', 'edit_/access/management/custom-plant-access']], function(){
        Route::any('/custom-plant-access/edit/{employee_id}/{menu_id}', [CustomAccess::class, 'update'])->name('custom-plant-access.update');
    });
});

Route::group(['middleware'=>[],'prefix' => 'api', 'namespace' => 'App\Http\Controllers\APIController'], function(){
    Route::get('/menu-rockbar', [APIController::class, 'menu_rockbar']);
    Route::get('/menu-rockbar/{section}', [APIController::class, 'menu_rockbar_section']);
    Route::get('/menu-liuli', [APIController::class, 'menu_liuli']);
    Route::get('/menu-liuli/{category}', [APIController::class, 'menu_liuli_category']);
});

//route untuk kebutuhan test
Route::get('/generate-password', [UserController::class, 'generate_password']);


Route::group(['middleware'=>[],'prefix' => 'notifications', 'namespace' => 'App\Http\Controllers\Notifications'], function(){
    Route::post('/read-notif', [Notifications::class, 'ajax_readNotif']);
});

// Route::get('/rockbar_menu', [Menu_outlet::class, 'index']);

//zoho route
Route::group(['prefix' => 'zoho_api', 'namespace' => 'App\Http\Controllers\ZohoController'], function(){
    Route::get('/', [ZohoController::class, 'index']);
    Route::get('/redirect', [ZohoController::class, 'redirect']);
    Route::get('/generate_token', [ZohoController::class, 'generate_access_token']);
    Route::get('/generate_authorization_code', [ZohoController::class, 'generate_authorization_code']);
    Route::get('/refresh_token', [ZohoController::class, 'refresh_token']);
    Route::get('/add_record', [ZohoController::class, 'add_record']);
    Route::get('/upload_document', [ZohoController::class, 'upload_document']);
    Route::get('/send_document_signature', [ZohoController::class, 'send_document_signature']);
    Route::get('/get_document_list', [ZohoController::class, 'get_document_list']);

});

Route::group(['middleware'=>[],'prefix' => 'zoho-api', 'namespace' => 'App\Http\Controllers\ZohoAPIController'], function(){
    Route::post('/formRequestEntertainment', [ZohoAPIController::class, 'formRequestEntertainment']);
});
//=====================================================

Route::group(['middleware'=>'auth.api', 'prefix' => 'sap', 'namespace' => 'App\Http\Controllers\SAP\RealEstate'], function(){
    // SAP Invoice
    Route::get('/invoice_list', [RealEstate::class, 'invoice_list'])->name('sap.invoice.list');
    Route::group(['permission'=>['view_/sap/invoice_list', 'create_/sap/invoice_list']], function(){
        // Route::get('/invoice_list_getData', [RealEstate::class, 'invoice_list_getData'])->name('sap.invoice.list.data');
        Route::get('/invoice_list/detail/{param}', [RealEstate::class, 'invoice_detail'])->name('sap.invoice.detail');
        // QAS
        Route::get('/invoice_list_qas', [RealEstate::class, 'invoice_list_qas'])->name('sap.invoice_qas.list');
        Route::get('/invoice_list_qas/detail/{param}', [RealEstate::class, 'invoice_detail_qas'])->name('sap.invoice_qas.detail');

        // PRE LIVE
        Route::get('/invoice_list_pre_live', [RealEstate::class, 'invoice_list_pre_live'])->name('sap.invoice_pre_live.list');
        Route::get('/invoice_list_pre_live/detail/{param}', [RealEstate::class, 'invoice_detail_pre_live'])->name('sap.invoice_pre_live.detail');
    });

    //SAP Invoice QAS
    // Route::get('/invoice_list_qas', [RealEstate::class, 'invoice_list_qas'])->name('sap.invoice_qas.list');
    // Route::group(['permission'=>['view_/sap/invoice_list_qas', 'create_/sap/invoice_list_qas']], function(){
    //     Route::get('/invoice_list_qas/detail/{param}', [RealEstate::class, 'invoice_detail_qas'])->name('sap.invoice_qas.detail');
    // });

    // // SAP Invoice PRE-LIVE
    // Route::get('/invoice_list_pre_live', [RealEstate::class, 'invoice_list_pre_live'])->name('sap.invoice_pre_live.list');
    // Route::group(['permission'=>['view_/sap/invoice_list_pre_live', 'create_/sap/invoice_list_pre_live']], function(){
    //     Route::get('/invoice_list_pre_live/detail/{param}', [RealEstate::class, 'invoice_detail_pre_live'])->name('sap.invoice_pre_live.detail');
    // });

    Route::get('/invoice/payment/status', [RealEstate::class, 'check_invoice_status_blade'])->name('sap.invoice.payment.status');

    // SAP Rental Object
    Route::get('/rental_object_list', [RealEstate::class, 'rental_object_list']);
    Route::get('/rental_object_list/filter_rental', [RealEstate::class, 'rental_object_list_filter']);
    Route::get('/rental_object_list/contract_detail', [RealEstate::class, 'rental_object_contract_detail']);

    // SAP Purchase Order
    Route::get('/purchase_order_list', [PurchaseOrder::class, 'index'])->name('sap.po.list');
    // Route::get('/purchase_order_list/detail/{no_po?}', [PurchaseOrder::class, 'po_detail'])->name('sap.po.detail');
    Route::get('/purchase_order_list/detail/{no_po?}', [PurchaseOrder::class, 'po_detail_json'])->name('sap.po.detail.json');

    Route::get('/cash_balance', [CashBalance::class, 'index'])->name('cash_balance.index');
    Route::get('/cash_balance/filter_cash_balance', [CashBalance::class, 'filter_cash_balance'])->name('cash_balance.filter');

    Route::get('/bank_balance', [CashBalance::class, 'index_new'])->name('cash_balance.index.new');
    Route::get('/cash_balance/new/filter_cash_balance', [CashBalance::class, 'filter_cash_balance_new'])->name('cash_balance.filter.new');

    Route::get('/inventory', [Inventory::class, 'index'])->name('sap.inventory');
    Route::post('/inventory/ajaxFilterCompany', [Inventory::class, 'ajax_filterCompany'])->name('sap.inventory.ajax_filterCompany');
    Route::post('/inventory/ajaxFilterPlant', [Inventory::class, 'ajax_filterPlant'])->name('sap.inventory.ajax_filterPlant');

    Route::get('/barcode_inventory', [Inventory::class, 'barcode_inventory'])->name('sap.barcode_inventory');
    Route::get('/report_mrp', [ReportMRP::class, 'index'])->name('sap.report_mrp');
    Route::get('/fb_flash_cost_report', [ReportController::class, 'fb_flash_cost_report'])->name('sap.fb_flash_cost_report');

    Route::get('/customer-ar-aging-summary', [ReportAging::class, 'report_ar_aging_vendor'])->name('sap.report_ar_aging_vendor');
    Route::get('/customer-ar-aging-summary/get-data', [ReportAging::class, 'report_ar_aging_vendor_getData'])->name('sap.report_ar_aging_vendor.getdata');

    Route::group(['prefix'=>'report_ar_aging', 'namespace' => 'App\Http\Controllers\Finance\ReportAging'], function(){
        Route::group(['permission'=>['view_/sap/report_ar_aging', 'create_/sap/report_ar_aging']], function(){
            Route::get('/', [ReportAging::class, 'ar_aging'])->name('sap.report_ar_aging');
            Route::get('/search-customer', [ReportAging::class, 'search_customer'])->name('sap.report_ar_aging.search_customer');
        });
    });

    Route::get('/customer-ap-aging-summary', [ReportAging::class, 'report_ap_aging_vendor'])->name('sap.report_ap_aging_vendor');
    Route::get('/customer-ap-aging-summary/get-data', [ReportAging::class, 'report_ap_aging_vendor_getData'])->name('sap.report_ap_aging_vendor.getdata');

    Route::group(['prefix'=>'report_ap_aging', 'namespace' => 'App\Http\Controllers\Finance\ReportAging'], function(){
        Route::group(['permission'=>['view_/sap/report_ap_aging', 'create_/sap/report_ap_aging']], function(){
            Route::get('/', [ReportAging::class, 'ap_aging'])->name('sap.report_ap_aging');
            Route::get('/search-customer', [ReportAging::class, 'search_customer'])->name('sap.report_ap_aging.search_customer');
        });
    });

    Route::group(['prefix' => 'finance', 'namespace' => 'App\Http\Controllers\Finance\PNL'], function(){
        Route::any('/pnl', [PNL::class, 'index'])->name('sap.report.pnl');
        Route::any('/pnl_cost', [PNL::class, 'pnl_cost'])->name('sap.report.pnl_cost');
        Route::any('/pnl-headcount/input', [PNL::class, 'headcount_input'])->name('sap.report.pnl.headcount_input');
    });

    Route::get('/menu-engineering/report', [MenuEngineering::class, 'index'])->name('sap.menu-engineering.report');

    Route::group(['middleware'=>'auth.api','prefix' => 'add-recipe', 'namespace' => 'App\Http\Controllers\SAP\Recipe'], function(){
        Route::get('/request', [Recipe::class, 'request'])->name('sap.add-recipe.request');
    });
});

/* WEBSERVICE ROUTE */
Route::group(['middleware'=>[],'prefix' => 'webservice', 'namespace' => 'App\Http\Controllers\WebService'], function(){
    Route::post('/talenta/employee', [EmployeeTalenta::class, 'index'])->name('talenta.api.employee');
    Route::post('/rental-object/revenue/{company_code?}', [RealEstate::class, 'refx_revenue'])->name('refx.api.revenue');
    Route::get('/sap/budgetactual', [BudgetActual::class, 'index'])->name('sap.api.budgetactual');
    Route::get('/sap/sync/budgetactual', [BudgetActual::class, 'sync_data_budget'])->name('sap.api.sync_budgetactual');

    Route::get('/book4time/b4tsummary', [Book4Time::class, 'summary'])->name('book4time.api.b4tsummary');
    Route::get('/book4time/b4tdetail', [Book4Time::class, 'detail'])->name('book4time.api.b4tdetail');
    Route::get('/book4time/b4ttax', [Book4Time::class, 'tax'])->name('book4time.api.b4ttax');
    Route::get('/book4time/appointment', [Book4Time::class, 'appointment'])->name('book4time.api.b4tappointment');
    Route::get('/book4time/appointmentABR', [Book4Time::class, 'appointmentABR'])->name('book4time.api.appointmentABR');
    Route::get('/book4time/MasterGuestType', [Book4Time::class, 'MasterGuestType'])->name('book4time.api.MasterGuestType');
    Route::get('/book4time/MasterCustomer', [Book4Time::class, 'MasterCustomer'])->name('book4time.api.MasterCustomer');

    Route::get('/sap/fnb-bom-cost', [FnbBomCost::class, 'index'])->name('sap.api.fnbbomcost');
    Route::post('/bi/exchange-rate/{exchange?}', [BIExchangeRate::class, 'index'])->name('bi.api.exchange');
    Route::get('/zapier/vcc_view', [Zapier::class, 'charge_virtualCreditCard_view'])->name('zapier.vcc_view');
    Route::post('/zapier/vcc_process', [Zapier::class, 'charge_virtualCreditCard_process'])->name('zapier.vcc_process');
    Route::get('/zapier/vcc_fetch_db', [Zapier::class, 'charge_virtualCreditCard_fetch_from_database'])->name('zapier.vcc_fetch_db');
    Route::get('/zapier/encrypt_vcc', [Zapier::class, 'enkripsi_data_vcc'])->name('zapier.enkripsi_data_vcc');
    // Route::get('/zapier/get_card_token', [Zapier::class, 'getCardToken'])->name('zapier.get_card_token');
    Route::get('/quinos/summary/transaction', [QuinosPOS::class, 'summary'])->name('quinos.api.summary');
    Route::get('/quinos/detail/transaction', [QuinosPOS::class, 'detail'])->name('quinos.api.detail');

    Route::get('/sap/material-cost', [MaterialLastPrice::class, 'index'])->name('sap.api.materialcost');
});

Route::group(['middleware'=>[],'prefix' => 'daily_report', 'namespace' => 'App\Http\Controllers\WebService'], function(){
    Route::get('/type/all', [ReportEmail::class, 'all'])->name('daily_report.type.all');
    Route::get('/type/summary', [ReportEmail::class, 'summary'])->name('daily_report.type.summary');
    Route::get('/type/outlet', [ReportEmail::class, 'outlet_revenue'])->name('daily_report.type.outlet');
    Route::get('/type/rental_object', [ReportEmail::class, 'rental_object'])->name('daily_report.type.rental_object');
    Route::get('/type/pnb1_revenue', [ReportEmail::class, 'pnb1_revenue'])->name('daily_report.type.pnb1_revenue');
    Route::get('/type/pad3_revenue', [ReportEmail::class, 'pad3_revenue'])->name('daily_report.type.pad3_revenue');
    Route::get('/type/pnl_cost', [PNL::class, 'pnl_cost_export'])->name('sap.report.pnl_cost.export');
    Route::get('/type/pnl', [PNL::class, 'pnl_export'])->name('sap.report.pnl.export');
});

Route::group(['middleware'=>'auth.api','prefix' => 'vcc-parsing', 'namespace' => 'App\Http\Controllers\VCC_Controller'], function(){
    Route::group(['permission'=>['view_/vcc-parsing', 'create_/vcc-parsing']], function(){
        Route::get('/data', [VCC_Controller::class, 'index'])->name('vcc-parsing.index');
        Route::post('/data/getData', [VCC_Controller::class, 'index_getData'])->name('vcc-parsing.index_getData');
        Route::get('/data/modal-detail', [VCC_Controller::class, 'modal_detail'])->name('vcc-parsing.data.modal_detail');
        Route::post('/data/update', [VCC_Controller::class, 'update'])->name('vcc-parsing.data.update');
    });
});

// Route::get('/payment/send_test_mail', [Folio::class, 'send_mail'])->name('send_email.test');
// Route::get('/report/mail/send/all', [ReportEmail::class, 'send_all'])->name('all.report.email');
// Route::get('/report/mail/send/outlet', [ReportEmail::class, 'send_outlet'])->name('outlet.report.email');
Route::get('/report/mail/send/all/separate', [ReportEmail::class, 'send_separate'])->name('all_separate.report.email');
Route::get('/report/mail/send/pnb1_revenue', [ReportEmail::class, 'send_pnb1_revenue'])->name('pnb1_revenue.report.email');
Route::get('/report/mail/send/pad3_revenue', [ReportEmail::class, 'send_pad3_revenue'])->name('pad3_revenue.report.email');
Route::get('/report/mail/send/rental_object', [ReportEmail::class, 'send_rental_object'])->name('rental_object.report.email');

// Route::get('/report/mail/send/outlet/separate', [ReportEmail::class, 'send_outlet_separate'])->name('outlet_separate.report.email');
Route::get('/report/mail/send/summary', [ReportEmail::class, 'send_summary'])->name('summary.report.email');
Route::get('/report/mail/send/pnl_cost', [PNL::class, 'pnl_cost_send'])->name('sap.report.pnl_cost.email');






