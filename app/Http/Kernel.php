<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'jwt_aut' =>\App\Http\Middleware\JwtMiddleware::class,
        'hr&manager'=>\App\Http\Middleware\hrManager::class,
        'hrOrmanagerOruser'=>\App\Http\Middleware\hrORmanagerORuser::class,
        'hrOrmanagerOrteacher'=>\App\Http\Middleware\hrORmanagerORteacher::class,
        'addUsers'=>\App\Http\Middleware\addUsersMiddleware::class,
        'showUsers'=>\App\Http\Middleware\showUsersMiddleware::class,
        'editUsers'=>\App\Http\Middleware\editUsersMiddleware::class,
        'deleteUsers'=>\App\Http\Middleware\deleteUsersMiddleware::class,
        'searchUsers'=>\App\Http\Middleware\searchUsersMiddleware::class,
        'addSubjects'=>\App\Http\Middleware\addSubjectsMiddleware::class,
        'showSubjects'=>\App\Http\Middleware\showSubjectsMiddleware::class,
        'editSubjects'=>\App\Http\Middleware\editSubjectsMiddleware::class,
        'deleteSubjects'=>\App\Http\Middleware\deleteSubjectsMiddleware::class,
 'getRoles'=>\App\Http\Middleware\getRolesMiddleware::class,
        'getAuth'=>\App\Http\Middleware\getAuthorizationsMiddleware::class,
        'addTimeMonitoring'=>\App\Http\Middleware\add_time_monitoring::class,
        'editTimeMonitoring'=>\App\Http\Middleware\edit_time_monitoring::class,
        'deleteTimeMonitoring'=>\App\Http\Middleware\delete_time_monitoring::class,
        'showTimeMonitoring'=>\App\Http\Middleware\show_time_monitoring::class,
        'importTimeMonitoring'=>\App\Http\Middleware\import_time_monitoring::class,
        'exportTimeMonitoring'=>\App\Http\Middleware\export_time_monitoring::class,
        'addTimeMonitoringTemplate'=>\App\Http\Middleware\export_time_monitoring_template::class,
        'addAdvance'=>\App\Http\Middleware\addAdvance::class,
        'updateAdvance'=>\App\Http\Middleware\updateAdvance::class,
        'showAdvance'=>\App\Http\Middleware\showAdvance::class,
        'addPancment'=>\App\Http\Middleware\addPanchment::class,
        'updatePancment'=>\App\Http\Middleware\updatePanchment::class,
        'showPancment'=>\App\Http\Middleware\showPanchment::class,
        'addReward'=>\App\Http\Middleware\addReward::class,
        'updateReward'=>\App\Http\Middleware\updateReward::class,
        'showReward'=>\App\Http\Middleware\showReward::class,
        'addWorkLeave'=>\App\Http\Middleware\addworkLeave::class,
        'updateWorkLeave'=>\App\Http\Middleware\editworkLeave::class,
        'showWorkLeave'=>\App\Http\Middleware\showworkLeave::class,
        'deleteWorkLeave'=>\App\Http\Middleware\deleteworkLeave::class,
        'advanceStatus'=>\App\Http\Middleware\advanceStatus::class,
        'panchmentStatus'=>\App\Http\Middleware\panchmentStatus::class,
        'rewardStatus'=>\App\Http\Middleware\rewardStatus::class,
        'addFinancialPeriodValidation'=>\App\Http\Middleware\financialPeriodCond::class,
        'addFinancialPeriod'=>\App\Http\Middleware\addFinancialPeriod::class,
        'extendFinancialPeriodCond'=>\App\Http\Middleware\extendFinancialPeriodValidation::class,
        'extendFinancialPeriod'=>\App\Http\Middleware\extendFinancialPeriod::class,
        'showFinancialPeriod'=>\App\Http\Middleware\showFinancialPeriod::class,
        'closeFinancialPeriod'=>\App\Http\Middleware\closeFinancialPeriod::class,
        'addFinancialAccount'=>\App\Http\Middleware\addFinancialAccount::class,
        'deleteFinancialAccount'=>\App\Http\Middleware\deleteFinancialAccount::class,
        'showFinancialAccount'=>\App\Http\Middleware\showFinancialAccount::class,
        'addFinancialOperation'=>\App\Http\Middleware\addFinancialOperation::class,
        'deleteFinancialOperation'=>\App\Http\Middleware\deleteFinancialOperation::class,
        'showFinancialOperation'=>\App\Http\Middleware\showFinancialOperation::class,
        'ensureClosing'=>\App\Http\Middleware\ensureClosedCond::class,
        'isAnOpenFP'=>\App\Http\Middleware\isAnOpenFinancialPeriod::class,
        'addFinancialOperationValidation'=>\App\Http\Middleware\addFinancialOperationValidation::class,
        'addPricingPlan'=>\App\Http\Middleware\addpricingPlan::class,
        'showPricingPlan'=>\App\Http\Middleware\showpricingPlan::class,
        'editPricingPlan'=>\App\Http\Middleware\editpricingPlan::class,
        'deletePricingPlan'=>\App\Http\Middleware\deletepricingPlan::class,
        'showSalary'=>\App\Http\Middleware\showSalary::class,

		/////////////////////////////////////////////////////////////////////////////
        'addClass'=>\App\Http\Middleware\createClass::class,
        'editeClass'=>\App\Http\Middleware\editeClass::class,
        'deleteClass'=>\App\Http\Middleware\deleteClass::class,
        'displayClass'=>\App\Http\Middleware\displayClass::class,

        'addType'=>\App\Http\Middleware\addType::class,
        'editeType'=>\App\Http\Middleware\editeType::class,
        'deleteType'=>\App\Http\Middleware\deleteType::class,
        'displayType'=>\App\Http\Middleware\displayType::class,
        'createCourse'=>\App\Http\Middleware\createCourse::class,
        'editeCourse'=>\App\Http\Middleware\editeCourse::class,
        'deleteCourse'=>\App\Http\Middleware\deleteCourse::class,
        'displayCourse'=>\App\Http\Middleware\displayCourse::class,
        'createStudent'=>\App\Http\Middleware\createStudent::class,
        'editeStudent'=>\App\Http\Middleware\editeStudent::class,
        'deleteStudent'=>\App\Http\Middleware\deleteStudent::class,
        'displayStudent'=>\App\Http\Middleware\displayStudent::class,
        'displayAllStudent'=>\App\Http\Middleware\displayAllStudent::class,
        'searcheStudent'=>\App\Http\Middleware\searchStudent::class,
        'createEvaluation'=>\App\Http\Middleware\createEvaluation::class,
        'sortStudentDependOnEvaluation'=>\App\Http\Middleware\sortStudentDependOnEvaluation::class,
        'createMonitoring'=>\App\Http\Middleware\createMonitoring::class,
        'createTest'=>\App\Http\Middleware\createTest::class,
        'showTest'=>\App\Http\Middleware\showTest::class,
        'createSession'=>\App\Http\Middleware\createSession::class,
        'editeSession'=>\App\Http\Middleware\editeSession::class,
        'deleteSesion'=>\App\Http\Middleware\deleteSesion::class,
        'showSession'=>\App\Http\Middleware\showSession::class,
        'showAllSesion'=>\App\Http\Middleware\showAllSesion::class,

        'createAdvertisment'=>\App\Http\Middleware\createAdvertisment::class,
        'editeAdvertisment'=>\App\Http\Middleware\editeAdvertisment::class,
        'deleteAdvertisment'=>\App\Http\Middleware\deleteAdvertisment::class,
        'showAdvertisment'=>\App\Http\Middleware\showAdvertisment::class,
        'showAllAdvertisment'=>\App\Http\Middleware\showAllAdvertisment::class,

	];
}
