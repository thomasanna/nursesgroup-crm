<?php

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
Route::get('/',['as'=>'login','uses'=>'LoginController@login']);

Route::post('check-login',['as'=>'checkLogin','uses'=>'LoginController@checkLogin']);

Route::get('new-login',['uses'=>'LoginController@newLogin']);

Route::group(['middleware'=>'admin'],function(){
  Route::get('logout',['as'=>'admin.logout','uses'=>'LoginController@logout']);
  Route::get('dashboard',['as'=>'home.dashboard','uses'=>'DashboardController@index']);
  Route::get('get-monthly-graph-data',['as'=>'graph.monthly.bookings','uses'=>'DashboardController@getMonthlyData']);
  Route::post('change-password',['as'=>'change.password','uses'=>'DashboardController@changePassword']);

  //BANDS
  Route::group(['as'=>'bands.','namespace'=>'Bands'],function(){
    Route::post('get-applicant-payrate',['as'=>'get.applicant.payrate','uses'=>'BandController@getPayRate']);
  });
  //BANDS
  // USERS
  Route::group(['as'=>'users.'],function(){
    Route::get('users',['as'=>'home','uses'=>'UserController@index']);
    Route::get('new-user',['as'=>'new','uses'=>'UserController@new']);
    Route::post('data-user',['as'=>'data','uses'=>'UserController@data']);
    Route::post('save-user',['as'=>'save','uses'=>'UserController@save']);
    Route::get('edit-user/{id}',['as'=>'edit','uses'=>'UserController@edit']);
    Route::post('update-user',['as'=>'update','uses'=>'UserController@update']);
    Route::get('delete-user/{id}',['as'=>'delete','uses'=>'UserController@delete']);
    Route::post('user-log-entry',['as'=>'log.entry','uses'=>'UserController@userLogEntry']);
    Route::post('get-user-log',['as'=>'get.user.log','uses'=>'UserController@getUserLog']);

  });
  // ROLES
  Route::group(['as'=>'roles.'],function(){
    Route::get('roles',['as'=>'home','uses'=>'RoleController@index']);
    Route::get('new-role',['as'=>'new','uses'=>'RoleController@new']);
    Route::post('data-role',['as'=>'data','uses'=>'RoleController@data']);
    Route::post('save-role',['as'=>'save','uses'=>'RoleController@save']);
    Route::get('edit-role/{id}',['as'=>'edit','uses'=>'RoleController@edit']);
    Route::post('update-role',['as'=>'update','uses'=>'RoleController@update']);
    Route::get('delete-role/{id}',['as'=>'delete','uses'=>'RoleController@delete']);
  });
  // ROLES

  // PERMISSIONS
  Route::group(['as'=>'permissions.'],function(){
    Route::get('permissions',['as'=>'home','uses'=>'PermissionController@index']);
    Route::get('new-permission',['as'=>'new','uses'=>'PermissionController@new']);
    Route::post('data-permission',['as'=>'data','uses'=>'PermissionController@data']);
    Route::post('save-permission',['as'=>'save','uses'=>'PermissionController@save']);
    Route::get('edit-permission/{id}',['as'=>'edit','uses'=>'PermissionController@edit']);
    Route::post('update-permission',['as'=>'update','uses'=>'PermissionController@update']);
    Route::get('delete-permission/{id}',['as'=>'delete','uses'=>'PermissionController@delete']);
  });

  // TAXYEARS

  Route::group(['as'=>'taxyears.'],function(){
    Route::get('taxyears',['as'=>'home','uses'=>'TaxyearController@index']);
    Route::post('data-taxyear',['as'=>'data','uses'=>'TaxyearController@data']);
    Route::get('new-taxyear',['as'=>'new','uses'=>'TaxyearController@new']);
    Route::post('save-taxyear',['as'=>'save','uses'=>'TaxyearController@save']);
    Route::get('edit-taxyear/{id}',['as'=>'edit','uses'=>'TaxyearController@edit']);
    Route::post('update-taxyear',['as'=>'update','uses'=>'TaxyearController@update']);
    Route::get('delete-taxyear/{id}',['as'=>'delete','uses'=>'TaxyearController@delete']);
  });

  //BRANCHES
  Route::group(['as'=>'branches.'],function(){
    Route::get('branches',['as'=>'home','uses'=>'BranchController@index']);
    Route::post('data-branch',['as'=>'data','uses'=>'BranchController@data']);
    Route::get('new-branch',['as'=>'new','uses'=>'BranchController@new']);
    Route::post('save-branch',['as'=>'save','uses'=>'BranchController@save']);
    Route::get('edit-branch/{id}',['as'=>'edit','uses'=>'BranchController@edit']);
    Route::post('update-branch',['as'=>'update','uses'=>'BranchController@update']);
    Route::get('delete-branch/{id}',['as'=>'delete','uses'=>'BranchController@delete']);
  });
  //ZONES
  Route::group(['as'=>'zones.'],function(){
    Route::get('branches/{id}/zones',['as'=>'home','uses'=>'ZoneController@index']);
    Route::post('data-branch/{id}/zone',['as'=>'data','uses'=>'ZoneController@data']);
    Route::post('get-branch-zones',['as'=>'get','uses'=>'ZoneController@get']);
    Route::get('new-branch/{id}/zone',['as'=>'new','uses'=>'ZoneController@new']);
    Route::post('save-branch-zone',['as'=>'save','uses'=>'ZoneController@save']);
    Route::get('edit-branch/zone/{zoneId}',['as'=>'edit','uses'=>'ZoneController@edit']);
    Route::post('update-branch-zone',['as'=>'update','uses'=>'ZoneController@update']);
    Route::get('delete-branch-zone/{id}',['as'=>'delete','uses'=>'ZoneController@delete']);
  });
  //CLIENTS
  Route::group(['as'=>'clients.','namespace'=>'Clients'],function(){
    Route::get('clients',['as'=>'home','uses'=>'ClientController@index']);
    Route::post('data-client',['as'=>'data','uses'=>'ClientController@data']);
    Route::get('new-client',['as'=>'new','uses'=>'ClientController@new']);
    Route::post('save-client',['as'=>'save','uses'=>'ClientController@save']);
    Route::get('edit-client/{id}',['as'=>'edit','uses'=>'ClientController@edit']);
    Route::post('update-client',['as'=>'update','uses'=>'ClientController@update']);
    Route::get('delete-client/{id}',['as'=>'delete','uses'=>'ClientController@delete']);
  });

  //CLIENT UNITS
  Route::group(['as'=>'client_units.','namespace'=>'Clients'],function(){
    Route::get('client/units',['as'=>'home','uses'=>'ClientUnitController@index']);
    Route::get('client/staff-units',['as'=>'staffs.home','uses'=>'ClientUnitController@indexStaff']);
    Route::post('client/data-unit',['as'=>'data','uses'=>'ClientUnitController@data']);
    Route::post('client/staff-data-unit',['as'=>'staff.data','uses'=>'ClientUnitController@dataStaff']);
    Route::get('client/new-unit',['as'=>'new','uses'=>'ClientUnitController@new']);
    Route::post('client/save-unit',['as'=>'save','uses'=>'ClientUnitController@save']);
    Route::get('client/edit-unit/{id}',['as'=>'edit','uses'=>'ClientUnitController@edit']);
    Route::post('client/update-unit',['as'=>'update','uses'=>'ClientUnitController@update']);
    Route::get('client/delete-unit/{id}',['as'=>'delete','uses'=>'ClientUnitController@delete']);
    Route::post('client/units/zone',['as'=>'get.by.zone','uses'=>'ClientUnitController@getUnitsWithZone']);
    Route::post('client/units/branches',['as'=>'get.units.by.branch','uses'=>'ClientUnitController@getUnitsWithBranch']);
    Route::post('get-unit-log',['as'=>'get.unit.log','uses'=>'ClientUnitController@getUnitLog']);
    Route::post('unit-log-entry',['as'=>'log.entry','uses'=>'ClientUnitController@unitLogEntry']);

  });

  //CLIENT UNIT PHONES
  Route::group(['as'=>'client_unit_contact.','namespace'=>'Clients'],function(){
    Route::get('client/unit/contacts/{unitId}/{from?}',['as'=>'home','uses'=>'ClientUnitContactController@index']);
    Route::post('get-client-contacts',['as'=>'get.booking','uses'=>'ClientUnitContactController@getContacts']);  //Using in New Booking
    Route::post('client/unit/data-contact/{unitId}',['as'=>'data','uses'=>'ClientUnitContactController@data']);
    Route::get('client/unit/new-contact/{unitId}',['as'=>'new','uses'=>'ClientUnitContactController@new']);
    Route::post('client/unit/save-contact',['as'=>'save','uses'=>'ClientUnitContactController@save']);
    Route::get('client/unit/edit-contact/{id}',['as'=>'edit','uses'=>'ClientUnitContactController@edit']);
    Route::post('client/unit/update-contact',['as'=>'update','uses'=>'ClientUnitContactController@update']);
    Route::get('client/unit/delete-contact/{id}',['as'=>'delete','uses'=>'ClientUnitContactController@delete']);
  });
  //CLIENT UNIT PAYMENTS
  Route::group(['as'=>'client_unit_payments.','namespace'=>'Clients'],function(){
    Route::get('client/unit/new-payment/{unitId}',['as'=>'new','uses'=>'ClientUnitPaymentController@new']);
    Route::post('client/unit/save-payment',['as'=>'save','uses'=>'ClientUnitPaymentController@save']);
  });
  //CLIENT UNIT PAYMENTS

  //CLIENT UNIT PAYMENTS
  Route::group(['as'=>'client_unit_schedules.','namespace'=>'Clients'],function(){
    Route::get('client/unit/new-schedule/{unitId}/{from?}',['as'=>'new','uses'=>'ClientUnitScheduleController@new']);
    Route::post('client/unit/save-schedule',['as'=>'save','uses'=>'ClientUnitScheduleController@save']);
  });
  //CLIENT UNIT PAYMENTS

  Route::group(['namespace'=>'Applicants'],function(){
    //APPLICANTS
    Route::group(['as'=>'applicants.'],function(){
      Route::get('hr/dashboard',['as'=>'dashboard','uses'=>'ApplicantController@dashBoard']);
      Route::get('applicants',['as'=>'home','uses'=>'ApplicantController@index']);
      Route::get('applicants-terminated',['as'=>'home.terminated','uses'=>'ApplicantController@indexTerminated']);
      Route::post('data-applicant',['as'=>'data','uses'=>'ApplicantController@data']);
      Route::post('data-applicant-terminated',['as'=>'data.terminated','uses'=>'ApplicantController@dataTerminated']);
      Route::get('new-applicant',['as'=>'new','uses'=>'ApplicantController@new']);
      Route::post('save-applicant',['as'=>'save','uses'=>'ApplicantController@save']);
      Route::get('edit-applicant/{id}/{fromPage?}',['as'=>'edit','uses'=>'ApplicantController@edit']);
      Route::post('update-applicant',['as'=>'update','uses'=>'ApplicantController@update']);
      Route::get('delete-applicant/{id}',['as'=>'delete','uses'=>'ApplicantController@delete']);
      Route::get('move-to-active-staff/{id}',['as'=>'move.active.staff','uses'=>'ApplicantController@moveToActiveStaff']);
      Route::get('move-to-terminated-applicant/{id}',['as'=>'move.terminated.applicant','uses'=>'ApplicantController@moveToTerminated']);
      Route::get('move-to-active-applicant/{id}',['as'=>'move.active.applicant','uses'=>'ApplicantController@moveToActiveApplicant']);
      Route::post('change-applicant-progress',['as'=>'change.applicant.progress','uses'=>'ApplicantController@changeApplicantProgress']);
    });

    //APPLICANTS TRAINING
    Route::group(['as'=>'applicant.training.','prefix'=>'applicant'],function(){
      Route::get('trainings/{id}',['as'=>'home','uses'=>'TrainingController@index']);
      Route::post('data-training',['as'=>'data','uses'=>'TrainingController@data']);
      Route::get('new-training/{id}',['as'=>'new','uses'=>'TrainingController@new']);
      Route::post('get-training-course',['as'=>'get.training.course','uses'=>'TrainingController@getCourse']);
      Route::post('save-training',['as'=>'save','uses'=>'TrainingController@save']);
      Route::get('edit-training/{id}',['as'=>'edit','uses'=>'TrainingController@edit']);
      Route::post('update-training',['as'=>'update','uses'=>'TrainingController@update']);
      Route::get('delete-training/{id}',['as'=>'delete','uses'=>'TrainingController@delete']);
    });

    //APPLICANTS DBS
    Route::group(['as'=>'applicant.dbs.','prefix'=>'applicant'],function(){
      Route::get('dbs/{id}',['as'=>'home','uses'=>'DbsController@index']);
      Route::post('data-dbs',['as'=>'data','uses'=>'DbsController@data']);
      Route::get('new-dbs/{id}',['as'=>'new','uses'=>'DbsController@new']);
      Route::post('save-dbs',['as'=>'save','uses'=>'DbsController@save']);
      Route::get('edit-dbs/{id}',['as'=>'edit','uses'=>'DbsController@edit']);
      Route::post('update-dbs',['as'=>'update','uses'=>'DbsController@update']);
      Route::get('delete-dbs/{id}',['as'=>'delete','uses'=>'DbsController@delete']);
    });

    //APPLICANTS REFERENCES
    Route::group(['as'=>'applicant.references.','prefix'=>'applicant'],function(){
      Route::get('reference/{id}',['as'=>'home','uses'=>'ReferenceController@index']);
      Route::post('data-reference',['as'=>'data','uses'=>'ReferenceController@data']);
      Route::get('new-reference/{id}',['as'=>'new','uses'=>'ReferenceController@new']);
      Route::post('save-reference',['as'=>'save','uses'=>'ReferenceController@save']);
      Route::get('edit-reference/{id}',['as'=>'edit','uses'=>'ReferenceController@edit']);
      Route::post('update-reference',['as'=>'update','uses'=>'ReferenceController@update']);
      Route::get('delete-reference/{id}',['as'=>'delete','uses'=>'ReferenceController@delete']);
    });

    //APPLICANTS REFERENCES
    Route::group(['as'=>'applicant.right.to.work.','prefix'=>'applicant'],function(){
      Route::get('right-to-work/{id}',['as'=>'form','uses'=>'RightToWorkController@form']);
      Route::post('update-right-to-work',['as'=>'update','uses'=>'RightToWorkController@update']);
    });
  });

  // DRIVERS
  Route::group(['namespace'=>'Drivers'],function(){
    //DRIVERS
    Route::group(['as'=>'drivers.'],function(){
      Route::get('drivers',['as'=>'home','uses'=>'DriverController@index']);
      Route::post('data-driver',['as'=>'data','uses'=>'DriverController@data']);
      Route::get('new-driver',['as'=>'new','uses'=>'DriverController@new']);
      Route::post('save-driver',['as'=>'save','uses'=>'DriverController@save']);
      Route::get('edit-driver/{id}/{fromPage?}',['as'=>'edit','uses'=>'DriverController@edit']);
      Route::post('update-driver',['as'=>'update','uses'=>'DriverController@update']);
      Route::post('change-driver-progress',['as'=>'change.progress','uses'=>'DriverController@changeApplicantProgress']);
    });

    //DRIVER TRAINING
    Route::group(['as'=>'driver.vehicles.','prefix'=>'driver'],function(){
      Route::get('vehicles/{id}',['as'=>'home','uses'=>'VehicleController@index']);
      Route::post('data-vehicle',['as'=>'data','uses'=>'VehicleController@data']);
      Route::get('new-vehicle/{id}',['as'=>'new','uses'=>'VehicleController@new']);
      Route::post('save-vehicle',['as'=>'save','uses'=>'VehicleController@save']);
      Route::get('edit-vehicle/{id}',['as'=>'edit','uses'=>'VehicleController@edit']);
      Route::post('update-vehicle',['as'=>'update','uses'=>'VehicleController@update']);
      Route::get('delete-vehicle/{id}',['as'=>'delete','uses'=>'VehicleController@delete']);
    });

    //DRIVER LICENCES
    Route::group(['as'=>'driver.licences.','prefix'=>'driver'],function(){
      Route::get('licences/{id}',['as'=>'home','uses'=>'LicenceController@index']);
      Route::post('data-licences',['as'=>'data','uses'=>'LicenceController@data']);
      Route::get('new-licence/{id}',['as'=>'new','uses'=>'LicenceController@new']);
      Route::post('save-licence',['as'=>'save','uses'=>'LicenceController@save']);
      Route::get('edit-licence/{id}',['as'=>'edit','uses'=>'LicenceController@edit']);
      Route::post('update-licence',['as'=>'update','uses'=>'LicenceController@update']);
      Route::get('delete-licence/{id}',['as'=>'delete','uses'=>'LicenceController@delete']);
    });

    //APPLICANTS REFERENCES
    // Route::group(['as'=>'applicant.references.','prefix'=>'applicant'],function(){
    //   Route::get('reference/{id}',['as'=>'home','uses'=>'ReferenceController@index']);
    //   Route::post('data-reference',['as'=>'data','uses'=>'ReferenceController@data']);
    //   Route::get('new-reference/{id}',['as'=>'new','uses'=>'ReferenceController@new']);
    //   Route::post('save-reference',['as'=>'save','uses'=>'ReferenceController@save']);
    //   Route::get('edit-reference/{id}',['as'=>'edit','uses'=>'ReferenceController@edit']);
    //   Route::post('update-reference',['as'=>'update','uses'=>'ReferenceController@update']);
    //   Route::get('delete-reference/{id}',['as'=>'delete','uses'=>'ReferenceController@delete']);
    // });

    //DRIVERS RIGHT TO WORK
    Route::group(['as'=>'driver.right.to.work.','prefix'=>'driver'],function(){
      Route::get('right-to-work/{id}',['as'=>'form','uses'=>'RightToWorkController@form']);
      Route::post('update-right-to-work',['as'=>'update','uses'=>'RightToWorkController@update']);
    });
  });
  // DRIVERS

  //STAFFS
  Route::group(['as'=>'staffs.','namespace'=>'Staffs'],function(){
    Route::get('staff-active/{searchKeyword?}',['as'=>'home.active','uses'=>'StaffController@indexActive']);
    Route::post('data-active-staff',['as'=>'data.active','uses'=>'StaffController@dataStaffActive']);
    Route::get('staff-inactive',['as'=>'home.inactive','uses'=>'StaffController@indexInactive']);
    Route::post('data-inactive-staff',['as'=>'data.inactive','uses'=>'StaffController@dataStaffInactive']);
    Route::get('staff-terminated',['as'=>'home.terminated','uses'=>'StaffController@indexTerminated']);
    Route::post('data-terminated-staff',['as'=>'data.terminated','uses'=>'StaffController@dataStaffTerminated']);

    Route::get('staff-all',['as'=>'home.all','uses'=>'AllStaffController@indexAll']);
    Route::post('data-all-staff',['as'=>'data.all','uses'=>'AllStaffController@dataStaffAll']);

    /***Staff-avail-data Route***/
    Route::post('available-data-all-staff',['as'=>'available.data.all','uses'=>'AllStaffController@availableDataStaffAll']);
    /***End of Staff-avail-data Route***/

    Route::post('staff-sms',['as'=>'send.sms','uses'=>'AllStaffController@sendSms']);
    Route::get('note-profile/{id}',['as'=>'note.profile','uses'=>'AllStaffController@noteProfile']);

    /***Staff-avail-routes***/
    Route::get('staff-availabilty',['as'=>'availabilty','uses'=>'StaffAvailabilityController@availabilty']);
    Route::get('staff-availabilty/{id}',['as'=>'getStaffById','uses'=>'StaffAvailabilityController@getStaffById']);
    /***End of Staff-avail-routes***/

    Route::get('staff-availabilty-view',['as'=>'availabilty.view','uses'=>'StaffAvailabilityController@availabilityView']);
    Route::post('staff-availabilty-post',['as'=>'availabilty.post','uses'=>'StaffAvailabilityController@availabiltyPost']);
    Route::get('staff-availabilty-report/{categoryId?}',['as'=>'availabilty.report','uses'=>'StaffAvailabilityController@availabilityReport']);

    Route::get('edit-staff/{id}/{searchKeyword?}',['as'=>'edit','uses'=>'StaffController@edit']);
    Route::post('update-staff',['as'=>'update','uses'=>'StaffController@update']);
    Route::get('delete-staff/{id}',['as'=>'delete','uses'=>'StaffController@delete']);

    Route::post('change-staff-progress',['as'=>'change.progress','uses'=>'StaffController@changeStaffProgress']);

    Route::get('staff-send-profile//{id}',['as'=>'send.profile','uses'=>'StaffController@sendProfile']);

    //STAFFS TRAINING
    Route::group(['as'=>'training.','prefix'=>'staff'],function(){
      Route::get('trainings/{id}/{searchKeyword?}',['as'=>'home','uses'=>'TrainingController@index']);
      Route::post('data-training',['as'=>'data','uses'=>'TrainingController@data']);
      Route::get('new-training/{id}',['as'=>'new','uses'=>'TrainingController@new']);
      Route::post('get-training-course',['as'=>'get.training.course','uses'=>'TrainingController@getCourse']);
      Route::post('save-training',['as'=>'save','uses'=>'TrainingController@save']);
      Route::get('edit-training/{id}',['as'=>'edit','uses'=>'TrainingController@edit']);
      Route::post('update-training',['as'=>'update','uses'=>'TrainingController@update']);
      Route::get('delete-training/{id}',['as'=>'delete','uses'=>'TrainingController@delete']);
    });

    //STAFFS DBS
    Route::group(['as'=>'dbs.','prefix'=>'staff'],function(){
      Route::get('dbs/{id}/{searchKeyword?}',['as'=>'home','uses'=>'DbsController@index']);
      Route::post('data-dbs',['as'=>'data','uses'=>'DbsController@data']);
      Route::get('new-dbs/{id}',['as'=>'new','uses'=>'DbsController@new']);
      Route::post('save-dbs',['as'=>'save','uses'=>'DbsController@save']);
      Route::get('edit-dbs/{id}',['as'=>'edit','uses'=>'DbsController@edit']);
      Route::post('update-dbs',['as'=>'update','uses'=>'DbsController@update']);
      Route::get('delete-dbs/{id}',['as'=>'delete','uses'=>'DbsController@delete']);
    });

    //STAFFS REFERENCES
    Route::group(['as'=>'references.','prefix'=>'staff'],function(){
      Route::get('reference/{id}/{searchKeyword?}',['as'=>'home','uses'=>'ReferenceController@index']);
      Route::post('data-reference',['as'=>'data','uses'=>'ReferenceController@data']);
      Route::get('new-reference/{id}',['as'=>'new','uses'=>'ReferenceController@new']);
      Route::post('save-reference',['as'=>'save','uses'=>'ReferenceController@save']);
      Route::get('edit-reference/{id}',['as'=>'edit','uses'=>'ReferenceController@edit']);
      Route::post('update-reference',['as'=>'update','uses'=>'ReferenceController@update']);
      Route::get('delete-reference/{id}',['as'=>'delete','uses'=>'ReferenceController@delete']);
    });

    //STAFFS REFERENCES
    Route::group(['as'=>'right.to.work.','prefix'=>'staff'],function(){
      Route::get('right-to-work/{id}/{searchKeyword?}',['as'=>'form','uses'=>'RightToWorkController@form']);
      Route::post('update-right-to-work',['as'=>'update','uses'=>'RightToWorkController@update']);
    });
  });


  //BOOKING
  Route::group(['as'=>'booking.','namespace'=>'Bookings'],function(){
    Route::get('roastering/dasboard',['as'=>'dashboard','uses'=>'BookingController@dashBoard']);
    Route::get('bookings/{searchKeyword?}',['as'=>'current','uses'=>'BookingController@currentBooking']);
    Route::post('data-bookings',['as'=>'data.current','uses'=>'BookingController@dataCurrentBooking']);
    Route::post('booking-log-entry',['as'=>'log.entry','uses'=>'BookingController@bookingLogEntry']);
    Route::post('save-unit-inform-log',['as'=>'save.unit.inform.log','uses'=>'BookingController@saveUnitInformedLog']);
    /*NEW BOOKING*/
    Route::get('new-booking',['as'=>'new.step.one','uses'=>'BookingController@newBookingStepOne']);
    Route::get('step-one-row',['as'=>'step.one.row','uses'=>'BookingController@StepOneRow']);
    Route::post('new-booking-action',['as'=>'step.one.action','uses'=>'BookingController@StepOneAction']);
    /*NEW BOOKING*/
    Route::post('store-checked-booking',['as'=>'save.checked','uses'=>'BookingController@checkBooking']);

    /*SEARCH BOOKING*/
    Route::get('search-staff/{id}/{searchKeyword?}',['as'=>'allocate.staff','uses'=>'SearchController@allocateStaff']);
    Route::get('clear-booking-staff/{id}/{searchKeyword?}',['as'=>'clear.staff','uses'=>'SearchController@clearBookingStaff']);
    Route::post('available-staff/{bookingId}',['as'=>'data.available.staff','uses'=>'SearchController@dataAvaialableStaff']);
    Route::post('permenent-staff/{bookingId}',['as'=>'data.permenent.staff','uses'=>'SearchController@dataPermanentStaff']);
    Route::post('priority-staff/{bookingId}',['as'=>'data.priority.staff','uses'=>'SearchController@dataPriorityStaff']);
    Route::post('prev-worked-staff/{bookingId}',['as'=>'data.prev.worked.staff','uses'=>'SearchController@dataPrevWorkedStaff']);
    Route::post('staff-in-zone/{bookingId}',['as'=>'data.in.zone.staff','uses'=>'SearchController@dataStaffInZone']);
    Route::post('store-checked-state',['as'=>'store.checked.state','uses'=>'SearchController@storeCheckedState']);
    Route::post('send-booking-sms',['as'=>'send.new.sms','uses'=>'SearchController@sendBookingSms']);   // SEARCH PREVIEW SMS
    Route::post('assign-staff',['as'=>'assign.staff','uses'=>'SearchController@assignStaff']);
    Route::post('search-update',['as'=>'search.update','uses'=>'SearchController@searchUpdate']);
    Route::post('staffstatus-change',['as'=>'step.one.changestatus','uses'=>'SearchController@changestatus']);

    Route::post('get-staff-log',['as'=>'get.staff.log','uses'=>'SearchController@getStaffLog']);
    Route::post('staff-log-entry',['as'=>'staff.log.entry','uses'=>'SearchController@staffLogEntry']);

    Route::post('make-unavailable-staff',['as'=>'make.unavailable.staff','uses'=>'SearchController@makeUnavailableStaff']);
    Route::post('get-staff-avaiblity',['as'=>'get.staff.avaiblity','uses'=>'SearchController@getStaffAvailabiltyModal']);
    Route::post('get-staff-history',['as'=>'get.staff.history','uses'=>'SearchController@getStaffCurrentHistoryModal']);


    /*SEARCH BOOKING*/

    Route::get('allocate-staff-confirm/{id}/{searchKeyword?}',['as'=>'allocate.staff.confirm','uses'=>'AllocateStaffController@viewAllocation']);
    Route::post('allocate-staff-save',['as'=>'allocate.staff.save','uses'=>'AllocateStaffController@saveAllocation']);
    Route::post('get-allocate-staff-info',['as'=>'staff.info','uses'=>'AllocateStaffController@getStaffInfo']);
    Route::post('get-staff-ta',['as'=>'staff.ta','uses'=>'AllocateStaffController@getStaffTA']);
    Route::get('change-confirm-staff/{id}',['as'=>'change.confirm.staff','uses'=>'AllocateStaffController@viewChangeConfirm']);
    Route::post('change-confirm-staff-save',['as'=>'change.confirm.staff.save','uses'=>'AllocateStaffController@saveChangeConfirm']);
    Route::post('get-driver-clubs',['as'=>'get.driver.clubs','uses'=>'AllocateStaffController@getDriverClubs']);

    Route::get('staff-allocation-report',['as'=>'allocation.report','uses'=>'AllocationReportController@allocationReport']);
    Route::get('staff-allocation-report-view',['as'=>'allocation.report.view','uses'=>'AllocationReportController@allocationReportView']);

    Route::post('bookings/get-single-booking',['as'=>'get.single','uses'=>'BookingController@getSingleBooking']);
    Route::post('bookings/get-booking-log',['as'=>'get.booking.log','uses'=>'BookingController@getBookingLog']);
    Route::post('bookings/change-staff-status',['as'=>'change.status','uses'=>'BookingController@changeBookingStatus']);
    Route::post('bookings/update-edit',['as'=>'update.edit','uses'=>'BookingController@updateEdit']);

    Route::get('send-final-sms/{id}',['as'=>'send.final.sms','uses'=>'SendInfoController@sendFinalSMS']);
    Route::get('send-profile/{id}',['as'=>'send.profile','uses'=>'SendInfoController@sendProfile']);
    Route::get('preview-send-sms/{id}/{page}/{searchKeyword?}',['as'=>'preview.send.sms','uses'=>'SendInfoController@previewSendSms']);

    Route::post('send-shift-confirm-sms',['as'=>'send.shift.confirm.sms','uses'=>'SendInfoController@sendShiftConfirmSMS']);
    Route::post('send-final-shift-confirm-sms',['as'=>'send.final.shift.confirm.sms','uses'=>'SendInfoController@sendFinalShiftConfirmSMS']);
    Route::post('send-transport-sms',['as'=>'send.transport.sms','uses'=>'SendInfoController@sendTransportSMS']);
    Route::post('send-payment-sms',['as'=>'send.payment.sms','uses'=>'SendInfoController@sendPaymentSMS']);
    Route::post('send-other-sms',['as'=>'send.other.sms','uses'=>'SendInfoController@sendOtherSMS']);


    Route::get('quote-preview',['as'=>'quote.preview','uses'=>'GenerateQuoteController@quotePreview']);
    Route::post('quote-preview-data',['as'=>'quote.preview.data','uses'=>'GenerateQuoteController@quotePreviewData']);
    Route::get('quote-generate',['as'=>'quote.generate','uses'=>'GenerateQuoteController@generateQuote']);
    Route::get('generate-payee-report',['as'=>'payee.report','uses'=>'GenerateQuoteController@downloadPayeeReport']);
    Route::get('generate-daily-report',['as'=>'daily.report','uses'=>'GenerateQuoteController@downloadDailyReport']);
    Route::get('generate-further-report',['as'=>'further.report','uses'=>'GenerateQuoteController@downloadFurtherReport']);
    Route::get('generate-unit-report',['as'=>'unit.report','uses'=>'GenerateQuoteController@fetchUnitReport']);
    Route::post('unit-report-data',['as'=>'unit.report.data','uses'=>'GenerateQuoteController@fetchUnitReportData']);
    Route::get('send-unit-report',['as'=>'send.unit.report','uses'=>'GenerateQuoteController@sendUnitReport']);
      // FS SMS
    Route::get('generate-fs-sms',['as'=>'generate.fs.sms','uses'=>'GenerateQuoteController@generateFsSmS']);
    Route::post('fs-sms-data',['as'=>'fs.sms.data','uses'=>'GenerateQuoteController@FsSmSData']);
    Route::post('send-fs-sms',['as'=>'send.fs.sms','uses'=>'GenerateQuoteController@sendFsSms']);
      // FS SMS
    // CURRENT BOOKING
  });
  //BOOKING

  Route::group(['as'=>'transportation.','namespace'=>'Transportation'],function(){
    Route::get('current-trips',['as'=>'current.trips','uses'=>'TransportationController@viewTransport']);
    Route::post('current-data',['as'=>'current.data','uses'=>'TransportationController@dataTransport']);
    Route::get('allocate-trip/{driverId}/{date}',['as'=>'allocate.trip','uses'=>'TransportationController@allocateTransport']);
    Route::post('allocate-action',['as'=>'allocate.action','uses'=>'TransportationController@allocateAction']);
    Route::post('save-order',['as'=>'save.order','uses'=>'TransportationController@saveOrder']);
    Route::get('send-trip-sms/{driverId}/{date}',['as'=>'send.trip.sms','uses'=>'TransportationController@sendTripSms']);
    Route::post('send-trip-sms-action',['as'=>'send.trip.sms.action','uses'=>'TransportationController@sendTripSmsAction']);
    Route::post('select-trip',['as'=>'save.checked','uses'=>'TransportationController@checkTransport']);
    Route::get('preview-sms',['as'=>'preview.sms','uses'=>'TransportationController@previewSMS']);
    Route::get('proceed-to-payment/{driverId}/{date}',['as'=>'proceed.payment','uses'=>'TransportationController@proceedPayment']);
    Route::post('proceed-to-payment-save',['as'=>'proceed.payment.save','uses'=>'TransportationController@proceedPaymentSave']);
    Route::get('proceed-to-payment-action/{driverId}/{date}',['as'=>'proceed.payment.action','uses'=>'TransportationController@proceedPaymentAction']);

    Route::get('completed-trips',['as'=>'completed.trips','uses'=>'CompletedController@viewCompleted']);
    Route::post('completed-data',['as'=>'completed.data','uses'=>'CompletedController@dataCompleted']);
    Route::get('review-completed-trips/{week}/{driver}',['as'=>'review.completed.trips','uses'=>'CompletedController@reviewCompleted']);
    Route::post('extract-completed-trips',['as'=>'extract.completed.trips','uses'=>'CompletedController@extractCompleted']);
    Route::get('mark-as-to-pay/{driverId}/{week}/{date}',['as'=>'mark.as.to.pay','uses'=>'CompletedController@markAsToPay']);
    Route::get('move-trip-week/{driverId}/{week}/{date}',['as'=>'move.trip.week','uses'=>'CompletedController@moveTripWeek']);
    Route::get('ra-view/{driverId}/{week}',['as'=>'ra.view','uses'=>'CompletedController@showRa']);
    Route::get('ra-download-pdf/{driverId}/{week}',['as'=>'ra.download.pdf','uses'=>'CompletedController@downloadRaPdf']);
    Route::get('ra-email/{driverId}/{week}',['as'=>'ra.email','uses'=>'CompletedController@sendEmailRa']);

    Route::get('record-payment/{driverId}/{date}',['as'=>'record.payment','uses'=>'CompletedController@recordPayment']);
    Route::post('record-payment-action',['as'=>'record.payment.action','uses'=>'CompletedController@recordPaymentAction']);

    Route::get('transport-archives',['as'=>'archives','uses'=>'ArchiveController@viewArchives']);
    Route::post('transport-archives-data',['as'=>'archives.data','uses'=>'ArchiveController@dataArchives']);
  });

  // TIMESHEETS
  Route::group(['as'=>'timesheet.','namespace'=>'Timesheets'],function(){
    Route::get('timesheets/list',['as'=>'list','uses'=>'TimesheetController@list']);
    Route::post('timesheet-data',['as'=>'data.list','uses'=>'TimesheetController@data']);
    Route::post('timesheet/get-timesheet', ['as' => 'get.timesheet','uses' => 'TimesheetController@getTimesheet']);
    Route::post('checkin-timesheet',['as'=>'checkin','uses'=>'TimesheetController@checkInAction']);
    Route::post('verify-timesheet',['as'=>'verify','uses'=>'TimesheetController@verifyAction']);
    Route::post('verify-timesheet-sms',['as'=>'verify.sms','uses'=>'TimesheetController@verifySMSAction']);
    Route::post('verify-timesheet-rejectSms',['as'=>'verify.rejectsms','uses'=>'TimesheetController@rejectSMSDetails']);
    Route::post('verify-timesheet-resendsms',['as'=>'verify.sms.resend','uses'=>'TimesheetController@verifyResendSMSAction']);
    Route::post('revert-timesheet',['as'=>'revert','uses'=>'TimesheetController@revertAction']);
    Route::post('timesheet-log-entry',['as'=>'log.entry','uses'=>'TimesheetController@timesheetLogEntry']);
    Route::post('get-timesheet-log',['as'=>'get.timesheet.log','uses'=>'TimesheetController@getTimesheetLog']);
  });
  // TIMESHEETS

  // PAYMENTS
  Route::group(['as'=>'payment.','namespace'=>'Payments'],function(){

    Route::get('payments/payee',['as'=>'payee.list','uses'=>'PayeeController@payeeList']);
    Route::post('payment-payee-data',['as'=>'payee.data.list','uses'=>'PayeeController@payeeData']);

    Route::post('payment-payee-check',['as'=>'payee.check','uses'=>'PayeeController@checkPayeePayment']);
    Route::post('payment-single',['as'=>'data.single','uses'=>'PayeeController@getSinglePayment']);
    Route::post('payment-payee-verify',['as'=>'payee.verify','uses'=>'PayeeController@varifyPayeePayment']);
    Route::post('payment-payee-save-approve',['as'=>'payee.save.approve','uses'=>'PayeeController@saveApprovePayeePayment']);
    Route::post('payment-payee-approve',['as'=>'payee.approve','uses'=>'PayeeController@approvePayeePayment']);

    Route::get('payments/payee/weeks',['as'=>'payee.weeks.list','uses'=>'PayeeController@payeeWeekList']);
    Route::post('payment-payee-weeks-data',['as'=>'payee.weeks.data.list','uses'=>'PayeeController@payeeWeekData']);

    Route::get('payments/payee/week/review/{week}/{staffId}',['as'=>'payee.week.review','uses'=>'PayeeController@payeeWeekReview']);
    Route::get('payment/weeks/payee-move-to-next-week/{paymentId}',['as'=>'payee.week.move.to.next.week','uses'=>'PayeeController@moveToNextWeek']);
    Route::get('payment/weeks/payee-move-to-archives/{paymentId}',['as'=>'payee.week.move.to.archives','uses'=>'PayeeController@moveToArchives']);
    Route::get('payment/weeks/payee-revert/{paymentId}',['as'=>'payee.week.revert','uses'=>'PayeeController@revertToVA']);
    Route::post('payment/weeks/bright-pay',['as'=>'payee.week.bright.pay','uses'=>'PayeeController@brightPayDetails']);


    Route::get('payee-ra/{week?}/{staffId?}',['as'=>'payee.ra','uses'=>'PayeeController@generateRAPayee']);
    Route::get('payee-ra-email/{week?}/{staffId?}',['as'=>'payee.ra.email','uses'=>'PayeeController@raEmailPayee']);
    Route::get('payee-ra-pdf/{week?}/{staffId?}',['as'=>'payee.ra.pdf','uses'=>'PayeeController@rAPdfPayee']);

    Route::get('payee-week-report/{week}',['as'=>'payee.week.report','uses'=>'PayeeController@weekReportPayee']);
    Route::get('payee-week-brightpay/{week}',['as'=>'payee.week.brightpay','uses'=>'PayeeController@weekPayeeBrightpay']);


    Route::get('payee-ra-record-payment/{week?}/{staffId?}',['as'=>'payee.ra.record.payment','uses'=>'PayeeController@raRecordPayment']);
    Route::post('payee-ra-record-payment-action',['as'=>'payee.ra.record.payment.action','uses'=>'PayeeController@raRecordPaymentAction']);

    Route::get('payee-archives',['as'=>'payee.archives','uses'=>'PayeeController@archives']);
    Route::post('payment-payee-archives-data',['as'=>'payee.archives.data','uses'=>'PayeeController@archivesData']);

    Route::get('payment-payee-archives-all/{staffId}',['as'=>'payee.archives.all','uses'=>'PayeeController@archivesAll']);
    Route::post('payment-payee-archives-weeks',['as'=>'payee.archives.weeks','uses'=>'PayeeController@archivesAllWeeks']);
    Route::post('payment-payee-archives-weeks-details',['as'=>'payee.archives.weeks.details','uses'=>'PayeeController@archivesAllWeeksDetails']);

    // SELFIES
    Route::get('payments/selfie',['as'=>'selfie.list','uses'=>'SelfieController@selfieList']);
    Route::post('payment-selfie-data',['as'=>'selfie.data.list','uses'=>'SelfieController@selfieData']);

    Route::post('payment-selfie-check',['as'=>'selfie.check','uses'=>'SelfieController@checkSelfiePayment']);
    Route::post('payment-selfie-single',['as'=>'selfie.data.single','uses'=>'SelfieController@getSingleselfiePayment']);
    Route::post('payment-selfie-verify',['as'=>'selfie.verify','uses'=>'SelfieController@varifySelfiePayment']);
    Route::post('payment-selfie-save-approve',['as'=>'selfie.save.approve','uses'=>'SelfieController@saveApproveSelfiePayment']);
    Route::post('payment-selfie-approve',['as'=>'selfie.approve','uses'=>'SelfieController@approveSelfiePayment']);

    Route::get('payments/selfie/weeks',['as'=>'selfie.weeks.list','uses'=>'SelfieController@selfieWeekList']);
    Route::post('payment-selfie-weeks-data',['as'=>'selfie.weeks.data.list','uses'=>'SelfieController@selfieWeekData']);

    Route::get('payments/selfie/week/review/{week}/{staffId}',['as'=>'selfie.week.review','uses'=>'SelfieController@selfieWeekReview']);
    Route::get('payment/weeks/selfie-move-to-next-week/{paymentId}',['as'=>'selfie.week.move.to.next.week','uses'=>'SelfieController@moveToNextWeek']);
    Route::get('payment/weeks/selfie-move-to-archives/{paymentId}',['as'=>'selfie.week.move.to.archives','uses'=>'SelfieController@moveToArchives']);
    Route::get('payment/weeks/selfie-revert/{paymentId}',['as'=>'selfie.week.revert','uses'=>'SelfieController@revertToVA']);

    Route::get('selfie-ra/{week?}/{staffId?}',['as'=>'selfie.ra','uses'=>'SelfieController@generateRASelfie']);
    Route::get('selfie-ra-email/{week?}/{staffId?}',['as'=>'selfie.ra.email','uses'=>'SelfieController@raEmailSelfie']);
    Route::get('selfie-ra-pdf/{week?}/{staffId?}',['as'=>'selfie.ra.pdf','uses'=>'SelfieController@rAPdfSelfie']);

    Route::get('selfie-week-report/{week}',['as'=>'selfie.week.report','uses'=>'SelfieController@weekReportSelfie']);
    Route::get('selfie-week-payment-report/{week}',['as'=>'selfie.week.payment.report','uses'=>'SelfieController@weekSelfiePaymentReport']);

    Route::get('selfie-ra-record-payment/{week?}/{staffId?}',['as'=>'selfie.ra.record.payment','uses'=>'SelfieController@raRecordPayment']);
    Route::post('selfie-ra-record-payment-action',['as'=>'selfie.ra.record.payment.action','uses'=>'SelfieController@raRecordPaymentAction']);

    Route::get('selfie-archives',['as'=>'selfie.archives','uses'=>'SelfieController@archives']);
    Route::post('payment-selfie-archives-data',['as'=>'selfie.archives.data','uses'=>'SelfieController@archivesData']);
    Route::get('payment-selfie-archives-all/{staffId}',['as'=>'selfie.archives.all','uses'=>'SelfieController@archivesAll']);
    Route::post('payment-selfie-archives-weeks',['as'=>'selfie.archives.weeks','uses'=>'SelfieController@archivesAllWeeks']);
    Route::post('payment-selfie-archives-weeks-details',['as'=>'selfie.archives.weeks.details','uses'=>'SelfieController@archivesAllWeeksDetails']);
  });
  // PAYMENTS

  Route::group(['as'=>'invoices.','namespace'=>'Invoices'],function(){
    Route::get('invoices/list',['as'=>'list','uses'=>'InvoiceController@invoiceList']);
    Route::post('invoices-data',['as'=>'data','uses'=>'InvoiceController@invoiceData']);
    Route::post('invoice-single',['as'=>'single','uses'=>'InvoiceController@getSinglePayment']);

    Route::post('invoice-verify',['as'=>'verify','uses'=>'InvoiceController@varifyInvoice']);
    Route::post('invoice-approve',['as'=>'approve','uses'=>'InvoiceController@approveInvoice']);

    Route::get('invoices/weekly/list',['as'=>'weekly.list','uses'=>'InvoiceWeeklyController@invoiceWeeklyList']);
    Route::post('invoices-weekly-data',['as'=>'weekly.data','uses'=>'InvoiceWeeklyController@invoiceWeeklyData']);
    Route::get('invoices/week/review/{week}/{unitId}',['as'=>'week.review','uses'=>'InvoiceWeeklyController@invoiceWeekReview']);
    Route::get('invoices/week/revert/{unitId}',['as'=>'week.revert','uses'=>'InvoiceWeeklyController@revertToVA']);
    Route::get('invoices/week/invoice/{week}/{unitId}',['as'=>'week.invoice','uses'=>'InvoiceWeeklyController@generateInvoice']);
    Route::get('invoice/weeks/move-to-next-week/{invoiceId}',['as'=>'week.move.to.next.week','uses'=>'InvoiceWeeklyController@moveToNextWeek']);
    Route::get('invoice/weeks/move-to-archives/{invoiceId}',['as'=>'week.move.to.archives','uses'=>'InvoiceWeeklyController@moveToArchives']);

    Route::get('invoice-weekly-pdf/{week?}/{unitId?}',['as'=>'weekly.pdf','uses'=>'InvoiceWeeklyController@invoicePdf']);

    Route::get('invoice-weekly-email/{week?}/{unitId?}',['as'=>'weekly.email','uses'=>'InvoiceWeeklyController@invoiceEmailWeekly']);

    Route::get('invoice-weekly-excel/{week?}/{unitId?}',['as'=>'weekly.excel','uses'=>'InvoiceWeeklyController@invoiceExcelWeekly']);
    Route::get('invoice-weekly-internal-excel/{month?}/{unitId?}',['as'=>'weekly.internal.excel','uses'=>'InvoiceWeeklyController@invoiceExcelInternalWeekly']);
    Route::get('invoices/weekly/record-payment/list/{month}/{unitId}',['as'=>'weekly.record-payment','uses'=>'InvoiceWeeklyController@recordPayment']);
    Route::post('invoices/weekly/record-payment/action',['as'=>'weekly.record.payment.action','uses'=>'InvoiceWeeklyController@recordPaymentActionWeekly']);



    Route::get('invoices/monthly/list',['as'=>'monthly.list','uses'=>'InvoiceMonthlyController@invoiceMonthlyList']);
    Route::post('invoices-monthly-data',['as'=>'monthly.data','uses'=>'InvoiceMonthlyController@invoiceMonthlyData']);
    Route::get('invoices/month/review/{month}/{unitId}',['as'=>'month.review','uses'=>'InvoiceMonthlyController@invoiceMonthReview']);
    Route::get('invoices/month/revert/{unitId}',['as'=>'month.revert','uses'=>'InvoiceMonthlyController@revertToVA']);

    Route::get('invoices/month/invoice/{month}/{unitId}',['as'=>'month.invoice','uses'=>'InvoiceMonthlyController@generateInvoice']);

    Route::get('invoice/months/move-to-next-month/{invoiceId}',['as'=>'month.move.to.next.month','uses'=>'InvoiceMonthlyController@moveToNextMonth']);
    Route::get('invoice/months/move-to-archives/{invoiceId}',['as'=>'month.move.to.archives','uses'=>'InvoiceMonthlyController@moveToArchives']);
    Route::get('invoice-monthly-email/{month?}/{unitId?}',['as'=>'monthly.email','uses'=>'InvoiceMonthlyController@invoiceEmailMonthly']);
    Route::get('invoice-monthly-pdf/{week?}/{unitId?}',['as'=>'monthly.pdf','uses'=>'InvoiceMonthlyController@invoicePdf']);

    Route::get('invoice-monthly-excel/{month?}/{unitId?}',['as'=>'monthly.excel','uses'=>'InvoiceMonthlyController@invoiceExcelMonthly']);
    Route::get('invoice-monthly-internal-excel/{month?}/{unitId?}',['as'=>'monthly.internal.excel','uses'=>'InvoiceMonthlyController@invoiceExcelInternalMonthly']);
    Route::get('invoices/monthly/record-payment/list/{month}/{unitId}',['as'=>'monthly.record-payment','uses'=>'InvoiceMonthlyController@recordPayment']);
    Route::post('invoices/monthly/record-payment/action',['as'=>'monthly.record.payment.action','uses'=>'InvoiceMonthlyController@recordPaymentActionMonthly']);
  });
});

Route::group(['prefix'=>'unit-area','namespace'=>'UnitArea','as'=>'unit.area.'],function(){

  Route::get('login',['as'=>'login.unitLogin','uses'=>'LoginController@login']);
  Route::post('check-login',['as'=>'checkLogin','uses'=>'LoginController@checkLogin']);
  Route::get('register',['uses'=>'LoginController@register']);
  Route::post('save-register',['as'=>'saveRegister','uses'=>'LoginController@saveRegister']);
  Route::get('reset-password',['uses'=>'LoginController@resetPassword']);

  Route::get('dashboard',['as'=>'dashboard','uses'=>'DashboardController@index']);
  Route::get('logout',['as'=>'logout','uses'=>'LoginController@logout']);

  Route::get('get-weekly-graph-data',['as'=>'graph.weekly.bookings','uses'=>'DashboardController@getWeeklyBookingGraphData']);
  Route::get('get-monthly-graph-data',['as'=>'graph.monthly.bookings','uses'=>'DashboardController@getMonthlyData']);
  // Route::get('logout',['as'=>'admin.logout','uses'=>'LoginController@logout']);

  Route::get('get-current-month-graph-data',['as'=>'graph.current.month.bookings','uses'=>'BudgetController@getCurrentMonthGraphData']);
  Route::get('get-next-month-graph-data',['as'=>'graph.next.month.bookings','uses'=>'BudgetController@getNextMonthGraphData']);


  Route::group(['as'=>'booking.'],function(){
    Route::get('bookings',['as'=>'list','uses'=>'BookingController@index']);
    Route::post('bookings-check',['as'=>'check','uses'=>'BookingController@checkBookings']);
    Route::post('bookings-data',['as'=>'data','uses'=>'BookingController@data']);
    Route::get('new-booking',['as'=>'new','uses'=>'BookingController@new']);
    Route::post('new-booking-action',['as'=>'new.action','uses'=>'BookingController@newAction']);

  });

  Route::group(['as'=>'account.','namespace'=>'Account'],function(){

    Route::get('my-account',['as'=>'account','uses'=>'AccountController@accountView']);
    Route::post('my-account/update',['as'=>'update','uses'=>'AccountController@update']);

  });

  Route::group(['as'=>'budget.'],function(){
      Route::get('budget',['as'=>'overview','uses'=>'BudgetController@overview']);
      Route::post('budget-set-action',['as'=>'set.action','uses'=>'BudgetController@setAction']);
      Route::post('monthly-bookings-data',['as'=>'monthlybooking.data','uses'=>'BudgetController@bookingData']);
      Route::post('booking-filter-month',['as'=>'filter.month','uses'=>'BudgetController@bookingFilterByMonth']);
  });

 


});
