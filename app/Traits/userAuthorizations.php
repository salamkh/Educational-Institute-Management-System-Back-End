<?php

namespace App\Traits;

use App\Models\authorization;
use App\Models\role;
use App\Models\teacher;
use App\Models\userauth;
use App\Models\userrole;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

trait userAuthorizations
{


	      //////////////////////////////////
          public function addClass (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إضافة فئة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function editeClass (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="تعديل فئة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function deleteClass (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="حذف فئة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function displayClass (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="استعراض فئة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
    
        public function addType (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إضافة فئة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function editeType (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="تعديل فئة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function deleteType (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="حذف فئة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function displayType (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="استعراض فئة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function addSubject (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إضافة مادة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function editeSubject (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="تعديل مادة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function deleteSubject (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="حذف مادة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function displaySubject (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="استعراض مادة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function createCourse (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إنشاء دورة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function closeCourse (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إغلاق دورة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function editeCourse (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="تعديل معلومات دورة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function deleteCourse (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="حذف دورة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function displayCourse (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="استعراض دورة"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function displayAllCourse (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="استعراض جميع الدورات ضمن نوع محدد"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        ///
        public function createStudent (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إنشاء طالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function deleteStudent (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="حذف طالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
    
        public function editeStudent (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="تعديل طالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function displayStudent (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="استعراض طالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function displayAllStudent (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="استعراض جميع طالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function searcheStudent (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name==" البحث عن طالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
    
        public function createEvaluation (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إنشاء تقييم"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function editeEvaluation (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="تعديل تقييم"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function showEvaluation (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="عرض تقييم"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function showAllEvaluation (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="عرض كل التقييم لطالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function sortStudentDependOnEvaluation (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="ترتيب الطلاب حسب التقييم"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
    
        public function createMonitoring (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إنشاء تفقد"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
    
        public function createTest (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إنشاء تسميع"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function editeTest (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="تعديل تسميع"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function showTest (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="عرض التسميع"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function showAllTest (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="عرض كل التسميعات لطالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
    
        public function createSession (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إنشاء تسميع"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function editeSession (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="تعديل تسميع"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function showSession (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="عرض التسميع"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function showAllSesion (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="عرض كل التسميعات لطالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function deleteSesion (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="حذف كل التسميعات لطالب"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
    
        public function createAdvertisment (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="إنشاء إعلان"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function editeAdvertisment (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="تعديل إعلان"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function showAdvertisment(){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="عرض الإعلان"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function showAllAdvertisment (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="عرض كل الإعلانات"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
        public function deleteAdvertisment (){
            $pass = false;
            $user = JWTAuth::parseToken()->authenticate();
            $userAuth = userauth::where('userId',$user->userId)->get();
            if (sizeof($userAuth)!=0){
                for($i=0;$i<sizeof($userAuth);$i++){
                    $auth  = authorization::find($userAuth[$i]->aId);
                    if($auth->name=="حذف اعلان"){
                        $pass=true;
                        break;
                    }
                }
            }
            return $pass;
        }
    
    
    
    public function addUser()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إضافة حساب") {
                    $pass = true;
                    break;
                }
            }
        }
        return $pass;
    }
    public function editUser()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تعديل حساب") {
                    $pass = true;
                    break;
                }
            }
        }
        return $pass;
    }
    public function deleteUser()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "حذف حساب") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showUser()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض الحسابات") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function searchUser()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "البحث عن المستخدمين ") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showAuth()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض الصلاحيات") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function editAuth()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تعديل صلاحيات المستخدمين") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showRoles()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض الأدوار") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function editRoles()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تعديل أدوار المستخدمين ") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function addSubjects()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إضافة مواد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function editSubjects()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تعديل مواد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function deleteSubjects()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "حذف مواد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showSubjects()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض جميع المواد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function addTimeMonitoring()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إضافة تفقد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function editTimeMonitoring()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تعديل تفقد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function deleteTimeMonitoring()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "حذف تفقد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showTimeMonitoring()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض تفقد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function exportTimeMonitoring()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تصدير تفقد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function exportTimeMonitoringTemplate()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تصدير نموذج تفقد") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function addAdvance()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إضافة سلفة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function updateAdvance()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تعديل سلفة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showAdvance()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض سلفة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function addpanchment()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إضافة حسم") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function updatepanchment()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تعديل حسم") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showpanchment()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض حسم") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function addReward()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إضافة مكافأة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function updateReward()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تعديل مكافأة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showReward()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض مكافأة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showWorkLeave()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض إجازة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function updateWorkLeave()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تعديل إجازة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function addWorkLeave()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إضافة إجازة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function deleteWorkLeave()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "حذف إجازة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function advanceStatus()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تغيير حالة سلفة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function panchmentStatus()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تغيير حالة حسم") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function rewardStatus()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تغيير حالة مكافأة") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function addFinancialPeriod()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "فتح دورة مالية") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function extendFinancialPeriod()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "تمديد دورة مالية") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showFinancialPeriod()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض دورة مالية") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function closeFinancialPeriod()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إغلاق دورة مالية") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function addFinancialAccount()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إضافة حساب مالي") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showFinancialAccount()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض حساب مالي") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function deleteFinancialAccount()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "حذف حساب مالي") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function searchFinancialAccount()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "البحث عن حساب مالي") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function addFinancialOperation()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "إضافة عملية مالية") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function deleteFinancialOperation()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "حذف عملية مالية") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
    public function showFinancialOperation()
    {
        $pass = false;
        $user = JWTAuth::parseToken()->authenticate();
        $userAuth = userauth::where('userId', $user->userId)->get();
        if (sizeof($userAuth) != 0) {
            for ($i = 0; $i < sizeof($userAuth); $i++) {
                $auth  = authorization::find($userAuth[$i]->aId);
                if ($auth->name == "عرض عملية مالية") {
                    $pass = true;
                }
            }
        }
        return $pass;
    }
}
