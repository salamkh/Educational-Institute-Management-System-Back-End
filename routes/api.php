<?php
use App\Http\Controllers\pricingPlanController;
use App\Http\Controllers\financialUsersOperationsController;
use App\Http\Controllers\SendSMSController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\accountsManagement;
use App\Http\Controllers\advanceController;
use App\Http\Controllers\authorizationsController;
use App\Http\Controllers\financialAccountsController;
use App\Http\Controllers\financialPeriodController;
use App\Http\Controllers\panchmentController;
use App\Http\Controllers\rewardController;
use App\Http\Controllers\rolesController;
use App\Http\Controllers\studentAuth;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\subjectsController;
use App\Http\Controllers\teachersController;
use App\Http\Controllers\timeMonitoringController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\workleaveController;
use App\Http\Controllers\AdvertismentController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\financialOperationsController;
use App\Http\Controllers\financialStudentOperationController;
use App\Http\Controllers\MyClassController;
use App\Http\Controllers\SchedualController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SessionStudentMonitoringController;
use App\Http\Controllers\tableColumnsController;
use App\Http\Controllers\TestController;

//hr section
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register'])->middleware('jwt_aut')->middleware('addUsers');
Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt_aut');
Route::get('myProfile', [AuthController::class, 'me'])->middleware('jwt_aut');
Route::get('userProfile/{id}', [accountsManagement::class, 'profile'])->middleware('jwt_aut')->middleware('showUsers');
Route::get('allUsers', [accountsManagement::class, 'getUsers'])->middleware('jwt_aut')->middleware('showUsers');
Route::delete('deleteUser/{id}', [accountsManagement::class, 'deleteUser'])->middleware('jwt_aut')->middleware('deleteUsers');
Route::post('searchUserByname', [accountsManagement::class, 'searchUserByname'])->middleware('jwt_aut')->middleware('searchUsers');
Route::get('searchUserByuserName', [accountsManagement::class, 'searchUserByuserName'])->middleware('jwt_aut')->middleware('searchUsers');
Route::get('searchUserByRole/{id}', [accountsManagement::class, 'searchUserByRole'])->middleware('jwt_aut')->middleware('searchUsers');
Route::patch('editProfil', [AuthController::class, 'editProfil'])->middleware('jwt_aut');
Route::patch('editUserProfil', [accountsManagement::class, 'editUserProfil'])->middleware('jwt_aut')->middleware('editUsers');
Route::get('allAuthorizations', [authorizationsController::class, 'getAuthorizations'])->middleware('jwt_aut')->middleware('getAuth');
Route::get('userAuthorizations/{id}', [authorizationsController::class, 'getUserAuth'])->middleware('jwt_aut')->middleware('showUsers');
Route::get('allRoles', [rolesController::class, 'getRoles'])->middleware('jwt_aut')->middleware('getRoles');
Route::get('userRoles/{id}', [rolesController::class, 'getUserRole'])->middleware('jwt_aut')->middleware('jwt_aut')->middleware('showUsers');
Route::post('addSubject', [subjectsController::class, 'addSubject'])->middleware('jwt_aut')->middleware('addSubjects');
Route::get('allSubjects', [subjectsController::class, 'allSubjects'])->middleware('jwt_aut')->middleware('showSubjects');
Route::patch('editSubject/{id}', [subjectsController::class, 'editSubject'])->middleware('jwt_aut')->middleware('editSubjects');
Route::delete('deleteSubject/{id}', [subjectsController::class, 'deleteSubject'])->middleware('jwt_aut')->middleware('deleteSubjects');
Route::post('addTeacher', [teachersController::class, 'addTeacher'])->middleware('jwt_aut')->middleware('addUsers');
Route::get('allTeachers', [teachersController::class, 'allTeachers'])->middleware('jwt_aut')->middleware('showUsers');
Route::get('teacherSubjects/{id}', [teachersController::class, 'teacherSubjects'])->middleware('jwt_aut')->middleware('showUsers');
Route::get('teacherexperience/{id}', [teachersController::class, 'teacherexperience'])->middleware('jwt_aut')->middleware('showUsers');
Route::patch('editTeacherExp/{id}', [teachersController::class, 'editTeacherExp'])->middleware('jwt_aut')->middleware('editUsers');
Route::post('editTeacherSubjects/{id}', [teachersController::class, 'editTeacherSubjects'])->middleware('jwt_aut')->middleware('editUsers');
Route::post('updateUserRoles/{id}', [accountsManagement::class, 'updateUserRoles'])->middleware('jwt_aut')->middleware('editUsers');
Route::post('updateUserAuths/{id}', [accountsManagement::class, 'updateUserِAuthorizations'])->middleware('jwt_aut')->middleware('editUsers');
Route::post('addTimeMonitoring', [timeMonitoringController::class, 'create'])->middleware('addTimeMonitoring');
Route::post('getTimeMonitoring', [timeMonitoringController::class, 'getTimeMon'])->middleware('jwt_aut')->middleware('showTimeMonitoring');
Route::post('deleteTimeMonitoring/{id}', [timeMonitoringController::class, 'deleteTM'])->middleware('jwt_aut')->middleware('deleteTimeMonitoring');
Route::patch('updateTimeMonitoring/{id}', [timeMonitoringController::class, 'updateTimeMonitoring'])->middleware('editTimeMonitoring');
Route::post('export', [timeMonitoringController::class, 'export'])->middleware('jwt_aut')->middleware('addTimeMonitoringTemplate');
Route::post('import', [timeMonitoringController::class, 'import']);//->middleware('jwt_aut')->middleware('importTimeMonitoring');
Route::get('timeMonitoringExport', [timeMonitoringController::class, 'TimeMonitorintExp'])->middleware('exportTimeMonitoring');
Route::post('getTimeMonitoringForperiodForAllUsers', [timeMonitoringController::class, 'getTimeMonitoringForperiodForAllUsers'])->middleware('jwt_aut')->middleware('showTimeMonitoring');
Route::post('getTimeMonitoringForperiodForUser/{id}', [timeMonitoringController::class, 'getTimeMonitoringForperiodForUser'])->middleware('jwt_aut')->middleware('showTimeMonitoring');
Route::post('get_user_timeMonitoring/{id}', [timeMonitoringController::class, 'getUserTimeMonitoring'])->middleware('jwt_aut')->middleware('showTimeMonitoring');
#advance
Route::post('add_advance', [advanceController::class, 'addAdvance'])->middleware('jwt_aut')->middleware('addAdvance');
Route::patch('update_advance/{id}', [advanceController::class, 'updateAdvance'])->middleware('jwt_aut')->middleware('updateAdvance');
Route::get('user_advances/{id}', [advanceController::class, 'showUserAdvances'])->middleware('jwt_aut')->middleware('showAdvance');
Route::get('user_paid_advancese/{id}', [advanceController::class, 'showUserAdvancesInRange'])->middleware('jwt_aut')->middleware('showAdvance');
Route::post('range_advances', [advanceController::class, 'RangeAdvances'])->middleware('jwt_aut')->middleware('showAdvance');
Route::post('change_advance_status/{id}', [advanceController::class, "changeAdvanceStatus"])->middleware('jwt_aut')->middleware('advanceStatus');
#panchment
Route::post('add_panchment', [panchmentController::class, 'addPanchment'])->middleware('jwt_aut')->middleware('addPancment');
Route::patch('update_panchment/{id}', [panchmentController::class, 'updatePancment'])->middleware('jwt_aut')->middleware('updatePancment');
Route::get('user_panchments/{id}', [panchmentController::class, 'showUserPanchent'])->middleware('jwt_aut')->middleware('showPancment');
Route::post('range_panchments', [panchmentController::class, 'RangePanchments'])->middleware('jwt_aut')->middleware('showPancment');
Route::get('user_panchments_notApplyed/{id}', [panchmentController::class, "showUserPanchentInRange"])->middleware('jwt_aut')->middleware('showPancment');
Route::post('change_panchment_status/{id}', [panchmentController::class, "changePanchmentStatus"])->middleware('jwt_aut')->middleware('panchmentStatus');
#Reward
Route::post('add_reward', [rewardController::class, 'addReward'])->middleware('jwt_aut')->middleware('addReward');
Route::patch('update_reward/{id}', [rewardController::class, 'updateReward'])->middleware('jwt_aut')->middleware('updateReward');
Route::get('user_rewards/{id}', [rewardController::class, 'showUserReward'])->middleware('jwt_aut')->middleware('showReward');
Route::post('range_rewards', [rewardController::class, 'RangeRewards'])->middleware('jwt_aut')->middleware('showReward');
Route::post('change_reward_status/{id}', [rewardController::class, "changeٌRewardStatus"])->middleware('jwt_aut')->middleware('rewardStatus');
Route::get('user_rewards_notPaid/{id}', [rewardController::class, "showUserRewardInRange"])->middleware('jwt_aut')->middleware('showReward');
#workLeave
Route::post('add_workLeave', [workleaveController::class, 'addWorkLeave'])->middleware('jwt_aut')->middleware('addWorkLeave');
Route::patch('edit_workLeave/{id}', [workleaveController::class, 'updateReward'])->middleware('jwt_aut')->middleware('updateWorkLeave');
Route::delete('delete_workLeave/{id}', [workleaveController::class, 'deleteWorkLeave'])->middleware('jwt_aut')->middleware('deleteWorkLeave');;
Route::get('user_workLeave/{id}', [workleaveController::class, 'showUserWorkLeave'])->middleware('jwt_aut')->middleware('showWorkLeave');
Route::post('range_workLeave', [workleaveController::class, 'rangeWorkLeave'])->middleware('jwt_aut')->middleware('showWorkLeave');;
//addition
Route::get('get_employees_names', [accountsManagement::class, 'getEmpNames'])->middleware('jwt_aut');
Route::post('getAlternativeTeacherForDay/{id}', [teachersController::class, 'getAlternativeTeachers'])->middleware('jwt_aut');
Route::get('get_teachers_names', [accountsManagement::class, 'getTeachersNames'])->middleware('jwt_aut');
Route::get('get_user_retired/{id}', [accountsManagement::class, 'showUserRetierd'])->middleware('jwt_aut')->middleware('showUsers');


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//learning section
Route::post('/createMyClass', [MyClassController::class, 'createMyClass'])
    ->middleware('jwt_aut')->middleware('addClass');
Route::post('/deleteMyClass/{id}', [MyClassController::class, 'destroy'])
    ->middleware('jwt_aut')->middleware('deleteClass');
Route::get('/searchClassByName/{name}', [MyClassController::class, 'search'])
    ->middleware('jwt_aut')->middleware('displayClass');
Route::get('/showClass/{id}', [MyClassController::class, 'show'])
    ->middleware('jwt_aut')->middleware('displayClass');
Route::get('/showAllClass', [MyClassController::class, 'showAllClasses'])
    ->middleware('jwt_aut')->middleware('displayClass');
Route::post('/editeMyClass/{id}', [MyClassController::class, 'edit'])
    ->middleware('jwt_aut')->middleware('editeClass');

//_____________________________Types requests_____________________________________
Route::post('/createType', [TypeController::class, 'create'])
    ->middleware('jwt_aut')->middleware('addType');

Route::get('/showType/{id}', [TypeController::class, 'show']);
Route::get('/showAllTypes', [TypeController::class, 'showAllTypes']);
Route::get('/searchTypeByName/{name}', [TypeController::class, 'search']);
Route::post('/editeTypes/{id}', [TypeController::class, 'edit'])
    ->middleware('jwt_aut')->middleware('editeType');

Route::post('/deleteType/{id}', [TypeController::class, 'destroy'])
    ->middleware('jwt_aut')->middleware('deleteType');

Route::post('/addSubjectToType',[subjectsController::class, 'addSubjectToType'])
    ->middleware('jwt_aut')->middleware('addType');

Route::get('/getSubjectsTeacher/{sujectId}', [subjectsController::class, 'getSubjectsTeacher'])
    ->middleware('jwt_aut')->middleware('addType');

//___________________________Course requests________________________________
Route::post('/createCourse', [CourseController::class, 'create'])
    ->middleware('jwt_aut')->middleware('createCourse');;
Route::get('/showCourse/{id}', [CourseController::class, 'show']);
Route::get('/showAllCourse', [CourseController::class, 'showAllCourse'])
    ->middleware('jwt_aut')->middleware('displayCourse');
Route::get('/showAllEnableCourse', [CourseController::class, 'showAllEnableCourse'])
    ->middleware('jwt_aut')->middleware('displayCourse');
Route::post('/editeCourse/{id}', [CourseController::class, 'edit'])
    ->middleware('jwt_aut')->middleware('editeType');
Route::post('/deleteCourse/{id}', [CourseController::class, 'destroy'])
    ->middleware('jwt_aut')->middleware('createCourse');
Route::get('/showCoursesInType/{id}', [CourseController::class, 'showCoursesInType'])
    ->middleware('jwt_aut')->middleware('displayCourse');
Route::get('/showSubjectsInType/{id}', [TypeController::class, 'showSubjectsInType'])
    ->middleware('jwt_aut')->middleware('displayCourse');
Route::post('/closeCourse/{id}', [CourseController::class, 'closeCourse'])
    ->middleware('jwt_aut')->middleware('createCourse');
Route::post('/startCourse/{id}', [CourseController::class, 'startCourse'])
    ->middleware('jwt_aut')->middleware('createCourse');


//___________________________Student requests______________________________
Route::post('/createStudent', [StudentController::class, 'create'])
    ->middleware('jwt_aut')->middleware('createStudent');

Route::post('/logInStudent', [studentAuth::class, 'studentLogin']);

Route::post('/addStudentToCourse', [StudentController::class, 'addStudentToCourse'])
    ->middleware('jwt_aut')->middleware('createStudent');

Route::post('/deleteStudentFromCourse/{courseId}/{studentId}', [StudentController::class, 'deleteStudentFromCourse'])
    ->middleware('jwt_aut')->middleware('createStudent');
Route::post('/editStudentInfo/{id}', [StudentController::class, 'editStudentInfo'])
    ->middleware('jwt_aut')->middleware('createStudent');
Route::get('/showStudent/{id}', [StudentController::class, 'show'])
    ->middleware('jwt_aut')->middleware('displayStudent');
Route::get('/showStudentsInCourse/{id}', [StudentController::class, 'showStudentsInCourse'])
    ->middleware('jwt_aut')->middleware('displayStudent');

Route::get('/showStudentsInCourseDependOnGender/{id}/{gender}', [StudentController::class, 'showStudentsInCourseDependOnGender'])
    ->middleware('jwt_aut')->middleware('displayStudent');

Route::get('/showStudentsInCourseAlphabetically/{id}', [StudentController::class, 'showStudentsInCourseAlphabetically'])
    ->middleware('jwt_aut')->middleware('displayStudent');

Route::post('/deleteStudent/{id}', [StudentController::class, 'delete'])
    ->middleware('jwt_aut')->middleware('deleteStudent');

Route::get('/showstudentCoursess/{id}', [StudentController::class, 'showstudentCoursess'])
    ->middleware('jwt_aut')->middleware('displayStudent');
Route::get('/searchStudentInCourse/{id}/{name}', [StudentController::class, 'searchStudentInCourse'])
    ->middleware('jwt_aut')->middleware('searcheStudent');
Route::get('/sortStudentsInCourseByTest/{id}', [StudentController::class, 'sortStudentsInCourseByTest'])
    ->middleware('jwt_aut')->middleware('displayStudent');

Route::get('/showAllStudentsAlphabetically', [StudentController::class, 'showAllStudentsAlphabetically'])
    ->middleware('jwt_aut')->middleware('displayStudent');

Route::get('/searchStudent/{name}', [StudentController::class, 'searchStudent'])
    ->middleware('jwt_aut')->middleware('displayStudent');

Route::get('/showAllStudentsDependOnGender/{gender}', [StudentController::class, 'showAllStudentsDependOnGender'])
    ->middleware('jwt_aut')->middleware('displayStudent');


//___________________________________Session________________________________
Route::post('/createSession', [SessionController::class, 'create'])
    ->middleware('jwt_aut')->middleware('createSession');
Route::get('/showSession/{id}', [SessionController::class, 'show'])
    ->middleware('jwt_aut')->middleware('showSession');

Route::get('/showAllSession/{id}', [SessionController::class, 'showAllSession'])
    ->middleware('jwt_aut')->middleware('showAllSesion');
Route::post('/editSession/{id}', [SessionController::class, 'edit'])
    ->middleware('jwt_aut')->middleware('editeSession');

Route::post('/deleteSession/{id}', [SessionController::class, 'destroy'])
->middleware('jwt_aut')->middleware('deleteSesion');


//______________________________Test____________________________________
Route::post('/createTest', [TestController::class, 'create'])
    ->middleware('jwt_aut')->middleware('createTest');

Route::get('/showAllTests/{sessionId}', [TestController::class, 'showAllTests'])
    ->middleware('jwt_aut')->middleware('showTest');
Route::get('/searchAboutStudent/{sessionId}/{name}', [TestController::class, 'searchAboutStudent'])
->middleware('jwt_aut')->middleware('showTest');

Route::get('/showStudentsInCourseDependOnGenderWithTest/{sessionId}/{gender}', [TestController::class, 'showStudentsInCourseDependOnGenderWithTest'])
    ->middleware('jwt_aut')->middleware('showTest');
Route::get('/showStudentTestsInCourse/{courseId}/{studentId}', [TestController::class, 'showStudentTestsInCourse'])
    ->middleware('jwt_aut')->middleware('showTest');

//_____________________________session_student_monitoring______________________
Route::post('/createSessionStudentMonitoring', [SessionStudentMonitoringController::class, 'create'])
    ->middleware('jwt_aut')->middleware('createMonitoring');

//_________________________________Evaluation_________________________________
Route::post('/createEvaluation', [EvaluationController::class, 'create'])
    ->middleware('jwt_aut')->middleware('createEvaluation');

Route::get('/sortAllStudentsEvaluationInCourse/{courseId}', [EvaluationController::class, 'sortAllStudentsEvaluationInCourse'])
    ->middleware('jwt_aut')->middleware('sortStudentDependOnEvaluation');


//__________________________________Advertisment______________________________

Route::post('/createAdvertisment', [AdvertismentController::class, 'create'])
    ->middleware('jwt_aut')->middleware('createAdvertisment');

Route::get('/showAdvertisment/{id}', [AdvertismentController::class, 'show']);

Route::post('/editeAdvertisment/{id}', [AdvertismentController::class, 'edit'])
    ->middleware('jwt_aut')->middleware('editeAdvertisment');

Route::get('/showAllAdvertisment', [AdvertismentController::class, 'showAllAdvertisment']);

Route::post('/deleteAdvertisment/{id}', [AdvertismentController::class, 'destroy'])
    ->middleware('jwt_aut')->middleware('deleteAdvertisment');


//___________________________SMS_____________________________


Route::post(
    '/sendMessage',
    [SendSMSController::class, 'sendMessage']
);
Route::get(
    '/showAllMessages',
    [SendSMSController::class, 'showAllMessages']
);
Route::get(
    '/showAllMessagesForStudent/{to}',
    [SendSMSController::class, 'showAllMessagesForStudent']
);




//___________________________________Calender__________________________________
Route::post('/createCalender', [CalenderController::class, 'create']);
Route::post('/editeCalender/{id}', [CalenderController::class, 'edit']);
Route::post('/deleteCalender/{id}', [CalenderController::class, 'destroy']);
Route::get('/showCalender/{date}', [CalenderController::class, 'show']);
Route::get('/showAllCalenders', [CalenderController::class, 'showAllCalenders']);

Route::get('/showSchedual', [SchedualController::class, 'showSchedual']);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//____________________________ACCOUNTING SECTION______________________________//
//Financial_Periods
Route::post('open_financial_period', [financialPeriodController::class, 'addFinancialPeriod'])->middleware('jwt_aut')->middleware('addFinancialPeriod')->middleware('addFinancialPeriodValidation');
Route::post('extend_financial_period/{id}', [financialPeriodController::class, 'extendFinancialPeriod'])->middleware('jwt_aut')->middleware('extendFinancialPeriod')->middleware('extendFinancialPeriodCond');
Route::get('show_financial_period', [financialPeriodController::class, 'getFinancialPeriod'])->middleware('jwt_aut')->middleware('showFinancialPeriod');
Route::get('get_Financial_Period_operations/{id}', [financialPeriodController::class, 'getFPoperations'])->middleware('jwt_aut')->middleware('showFinancialPeriod');
Route::get('show_Financial_Period/{id}', [financialPeriodController::class, 'showFinancialPeriod'])->middleware('jwt_aut')->middleware('showFinancialPeriod');
Route::post('close_Financial_Period/{id}', [financialPeriodController::class, 'closeFinancialPeriod'])->middleware(['jwt_aut','closeFinancialPeriod']);
//Financial_Accounts
Route::middleware(['jwt_aut'])->group(function () {
    Route::post('add_Financial_account', [financialAccountsController::class, 'addFinancialAccount'])->middleware('addFinancialAccount');
    Route::delete('delete_financial_account/{id}', [financialAccountsController::class, 'deleteFinancialAccount'])->middleware('deleteFinancialAccount')->middleware('ensureClosing');
    Route::get('get_All_Financial_Accounts', [financialAccountsController::class, 'getAllFinancialAccounts'])->middleware('showFinancialAccount');
    Route::post('get_Financial_Accounts_By_Type', [financialAccountsController::class, 'getFinancialAccountsByType'])->middleware('showFinancialAccount');
    Route::get('get_All_Employee_Financial_Accounts', [financialAccountsController::class, 'getAllEmployeesFinancialAccounts'])->middleware('showFinancialAccount');
    Route::post('search_Financial_Accounts_By_name', [financialAccountsController::class, 'searchFinancialAccountsByName'])->middleware('showFinancialAccount');
    Route::get('get_Financial_Users_Accounts', [financialAccountsController::class, 'getAllUsersFinancialAccounts'])->middleware('showFinancialAccount');
    Route::get('get_Financial_Students_Accounts', [financialAccountsController::class, 'getAllStudentsFinancialAccounts'])->middleware('showFinancialAccount');
    Route::get('get_Financial_Types_Accounts', [financialAccountsController::class, 'getAllTypeFinancialAccounts'])->middleware('showFinancialAccount');
    Route::get('get_Financial_Teachers_Accounts', [financialAccountsController::class, 'getAllTeacherFinancialAccounts'])->middleware('showFinancialAccount');
    Route::get('get_Financial_Students_Accounts_Belong_Type/{id}', [financialAccountsController::class, 'getStudentsAccountsBelongToType'])->middleware('showFinancialAccount');
});
//Financial_Operations
Route::middleware(['jwt_aut', 'ensureClosing', 'isAnOpenFP'])->group(function () {
    Route::post('add_Financial_Operation', [financialOperationsController::class, 'addFinancialOperation'])->middleware('addFinancialOperation')->middleware('addFinancialOperationValidation');
    Route::delete('delete_Financial_Operation/{id}', [financialOperationsController::class, 'deleteFinancialOperation'])->middleware('deleteFinancialOperation');
    Route::get('Financial_Operations_Of_Open_Period', [financialOperationsController::class, 'getFinancialOperationsOfOpenPeriod'])->middleware('showFinancialOperation');
    Route::get('Financial_Operations_On_Account_In_OpenPeriod/{id}', [financialOperationsController::class, 'getFinancialOperationsOnAccountInOpenPeriod'])->middleware('showFinancialOperation');
    //studentOperations  
    Route::post('add_financial_student_operation', [financialStudentOperationController::class, 'addStudentFOperation'])->middleware('addFinancialOperation');
    Route::delete('delete_Financial_Student_Operation/{id}', [financialStudentOperationController::class, 'deleteStudentOPeration'])->middleware('deleteFinancialOperation');
    Route::get('students_depts_in_type_open_period/{id}', [financialStudentOperationController::class, 'showStudentDeptsInTypeInOpenPeriod'])->middleware(['jwt_aut', 'showFinancialOperation']);
    Route::get('students_depts_in_type/{id}', [financialStudentOperationController::class, 'showStudentDeptsInType'])->middleware(['jwt_aut', 'showFinancialOperation']);
    Route::get('student_Dept_For_Type/{id1}/{id2}', [financialStudentOperationController::class, 'studentDeptForType'])->middleware(['jwt_aut', 'showFinancialOperation']);
    //salaryOperation
    Route::post("pay_salary",[financialUsersOperationsController::class,'paySalary'])->middleware('addFinancialOperation');
});
//userSalary
Route::get('get_Employee_Salary/{id}', [financialUsersOperationsController::class, 'getEmployeeSalary'])->middleware(['jwt_aut', 'showSalary']);
Route::post('get_Teacher_Salary/{id}', [financialUsersOperationsController::class, 'getTeacherSalary'])->middleware(['jwt_aut', 'showSalary']);

//Reports
Route::post('Financial_Operations_In_Range', [financialOperationsController::class, 'getFinancialOperationsInRange'])->middleware(['jwt_aut', 'showFinancialOperation']);
Route::post('Financial_Operations_On_Account_In_Range/{id}', [financialOperationsController::class, 'getFinancialOperationsOnAccountInRange'])->middleware(['jwt_aut', 'showFinancialOperation']);
Route::get('students_depts_in_type/{id}', [financialStudentOperationController::class, 'showStudentDeptsInType'])->middleware(['jwt_aut', 'showFinancialOperation']);
Route::post('show_Student_Operation_For_Type',[financialStudentOperationController::class,'showStudentOperationForType'])->middleware(['jwt_aut', 'showFinancialOperation']);
Route::get('show_actually_result',[financialPeriodController::class,'getResult'])->middleware(['jwt_aut']);

//metaData
Route::post('add_Column', [tableColumnsController::class, "addColumn"])->middleware('jwt_aut');
Route::get('get_Dynamic_Tables', [tableColumnsController::class, "getDynamicTables"])->middleware('jwt_aut');
Route::get('get_Table_Columns/{id}', [tableColumnsController::class, "getTableColumns"])->middleware('jwt_aut');
Route::delete('delete_Column/{id}', [tableColumnsController::class, "deleteColumn"])->middleware('jwt_aut');
Route::get('get_Table_Columns_For_Addition/{id}', [tableColumnsController::class, "getTableColumnsForAddition"])->middleware('jwt_aut');
Route::get('get_Table_Columns_For_User_Eddition/{id}', [tableColumnsController::class, "getTableColumnsForEddition"])->middleware('jwt_aut');
Route::get('get_Table_Columns_For_User_Profile', [tableColumnsController::class, "getTableColumnsForProfile"])->middleware('jwt_aut');
//planningCost
Route::post('add_pricing_plan', [pricingPlanController::class, "addPlan"])->middleware(['jwt_aut', 'addPricingPlan']);
Route::get('get_pricing_plan', [pricingPlanController::class, "showPricingPlans"])->middleware(['jwt_aut', 'showPricingPlan']);
Route::delete('delete_pricing_plan/{id}', [pricingPlanController::class, "deletePricingPlan"])->middleware(['jwt_aut', 'deletePricingPlan']);
Route::post('add_domain_To_pricing_plan/{id}', [pricingPlanController::class, "addDomainToPlan"])->middleware(['jwt_aut', 'editPricingPlan']);
Route::delete('delete_domain_from_pricing_plan/{id}', [pricingPlanController::class, "deleteDomainFromPlan"])->middleware(['jwt_aut', 'editPricingPlan']);
Route::get('show_courses_to_add_to_courses',[pricingPlanController::class, "getCoursesToAddToPlan"]);
Route::post('add_course_to_plan', [pricingPlanController::class, "addCourseToPlan"])->middleware(['jwt_aut', 'editPricingPlan']);
Route::post('delete_course_from_plan', [pricingPlanController::class, "deleteCoursefromPlan"])->middleware(['jwt_aut', 'editPricingPlan']);






