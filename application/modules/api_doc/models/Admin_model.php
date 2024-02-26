<?php
////////////////////////////////////////
//                                    //
//      Modified by: Shashank Mishra //
//      Created On : 20 FEB 2021      //
//                                    //
/////////////////////////////////////-->


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    
    
    function is_login_success($inputs){
        $this->db->group_start()
                ->where('email', $inputs['username'])
                ->or_where('mobile', $inputs['username'])
                ->group_end();
        //$this->db->where('user_type', 2);
        $this->db->where('password', md5($inputs['password']));
        $row = $this->db->get('users')->row();
        if (empty($row)) {
            $this->db->group_start()
                    ->where('email', $inputs['username'])
                    //->or_where('user_name', $inputs['username'])
                    ->group_end();
            $this->db->where('password', md5($inputs['password']));
            $row = $this->db->get('backend_user')->row();
        }        
        return $row;
    }
    
    
    
    function createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height, $background = false) {
        list($original_width, $original_height, $original_type) = getimagesize($filepath);
        if ($original_width > $original_height) {
            $new_width = $thumbnail_width;
            $new_height = intval($original_height * $new_width / $original_width);
        } else {
            $new_height = $thumbnail_height;
            $new_width = intval($original_width * $new_height / $original_height);
        }
        $dest_x = intval(($thumbnail_width - $new_width) / 2);
        $dest_y = intval(($thumbnail_height - $new_height) / 2);

        if ($original_type === 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        } else if ($original_type === 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        } else if ($original_type === 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        } else {
            return false;
        }

        $old_image = $imgcreatefrom($filepath);
        $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height); // creates new image, but with a black background
        // figuring out the color for the background
        if (is_array($background) && count($background) === 3) {
            list($red, $green, $blue) = $background;
            $color = imagecolorallocate($new_image, $red, $green, $blue);
            imagefill($new_image, 0, 0, $color);
            // apply transparent background only if is a png image
        } else if ($background === 'transparent' && $original_type === 3) {
            imagesavealpha($new_image, TRUE);
            $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $color);
        }

        imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
        $imgt($new_image, $thumbpath);
        return file_exists($thumbpath);
    }

    function createDateRangeArray($strDateFrom, $strDateTo) {
        $aryRange = array();
        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom < $iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }
    
    function check_get_students_input_params($documents){
        if(isset($documents['school_id']) and !empty($documents['school_id'])){
            $this->db->where('students.schoolId', $documents['school_id']);
        }        
        if(isset($documents['class_ids']) and !empty($documents['class_ids'])){
            $this->db->where_in('students.classId', $documents['class_ids']);
        }else if(isset($documents['class_id']) and !empty($documents['class_id'])){
            $this->db->where('students.classId', $documents['class_id']);
        }        
        if(isset($documents['id']) and !empty($documents['id'])){
            $this->db->where('students.id', $documents['id']);
        }
        if(isset($documents['parent_id']) and !empty($documents['parent_id'])){
            $this->db->where('parents.userId', $documents['parent_id']);
        }        
        if(isset($documents['route_ids']) and !empty($documents['route_ids'])){
            $this->db->where_in('students.route', $documents['route_ids']);
        }else if(isset($documents['route_id']) and !empty($documents['route_id'])){
            $this->db->where('students.route', $documents['route_id']);
        }
    }
    //using->[contact_user->routes->find studens on selected route]
    function get_students($documents) { 
        $this->db->select('students.*,users.*,parents.*');
        $this->check_get_students_input_params($documents);//$this->db->select('');        
        $this->db->join('parents', 'parents.studentId=students.id');
        $this->db->join('users', 'users.id=parents.userId');
        $this->db->order_by('students.id', 'desc');
        $result = $this->db->get('students')->result_array(); //echo "1";pre($result);
        return $result;
    }
    
    function check_get_teachers_input_params($documents){
        if(isset($documents['school_id']) and !empty($documents['school_id'])){
            $this->db->where('teachers.schoolId', $documents['school_id']);
        }      
        if(isset($documents['ids']) and !empty($documents['ids'])){
            $this->db->where_in('teachers.id', $documents['ids']);
        }
    }
    function get_teachers($documents) { 
        $this->db->select('users.*,teachers.*');
        $this->check_get_teachers_input_params($documents);//$this->db->select('');        
        $this->db->join('teachers', 'users.id = teachers.userId');
        $this->db->order_by('users.id', 'desc');
        $result = $this->db->get('users')->result_array(); //echo "1";pre($result);
        return $result;        
    }
    
    public function is_school_name_address_already($document) {
        //pre($document);
        $this->db->like('name', $document['name']);
        $this->db->like('address', $document['address']);
        $result = $this->db->get('schools')->row_array();
        return (!empty($result)) ? $result : false;
    }
    public function is_email_already($document) {
        $this->db->where('email', $document['email']);
        $result = $this->db->get('users')->row_array();
        return (!empty($result)) ? $result : false;
    }
    function get_admin_by_school_id($document) {   //pre($document);die('1');
        $this->db->select("schools.*,users.*,users.id as adminId,users.name as adminName,users.profileImage");
        $this->db->where('schools.id', $document['schoolId']);
        $this->db->join('admins', 'admins.schoolId = schools.id');
        $this->db->join('users', 'users.id = admins.userId');
        $admin = $this->db->get('schools')->row_array(); //pre($admin); die('1');
        return ($admin) ? $admin : false;
    }

    function get_class_details_by_class_id($document) {
        $this->db->where('classes.id', $document['classId']);
        $class = $this->db->get('classes')->row_array();
        if ($class) {
            $student_ids = $teacher_ids = $subject_ids = $final_students = array();
            $this->db->select('students.route,students.id as student_id,students.name as student_name,students.fatherName,students.motherName,students.address,students.phone as student_phone,users.mobile as parent_phone,studentImage,users.id as parent_id,users.name as parent_name,profileImage,users.deviceType,users.deviceToken,users.email');
            $this->db->where('students.classId', $document['classId']);
            $this->db->join('parents', 'parents.studentId = students.id');
            $this->db->join('users', 'users.id = parents.userId');
            $studentss = $this->db->get('students')->result_array();
            if ($studentss) {
                foreach ($studentss as $student) {
                    //$route                  = array();
                    $student['routeName'] = "None";
                    if ($student['route'] != 0) {
                        $route = $this->getRoute($student['route']);
                        if ($route) {
                            $student['route'] = $route;
                            $student['routeName'] = $route['routename'];
                        }
                        //$student['route']       = $route;
                        //$student['routeName']   = $route['routename'];
                    }
                    //$student[]                 = $student;
                    $student_ids[] = $student['student_id'];
                    $student['studentImage'] = base_url() . "profileImages/thumbnails/" . $student['studentImage'];
                    $student['profileImage'] = base_url() . "profileImages/thumbnails/" . $student['profileImage'];
                    $final_students[] = $student;
                }
            }
            $class['students'] = $final_students;
            $this->db->select('lecture.day,lecture.startTime,lecture.endTime,lecture.duration,lecture.subjectId,lecture.classId,lecture.teacherId');
            $this->db->where('lecture.classId', $document['classId']);
            //$this->db->join('classes','classes.id = lecture.classId');
            //$this->db->join('subjects','subjects.id = lecture.subjectId');
            $lectures = $this->db->get('lecture')->result_array();
            if ($lectures) {
                foreach ($lectures as $lecture) {
                    //$class_ids[] = $lecture['classId'];
                    $teacher_ids[] = $lecture['teacherId'];
                    $subject_ids[] = $lecture['subjectId'];
                }
                //$class_ids = array_unique($class_ids);
                $teacher_ids = array_unique($teacher_ids);
                $subject_ids = array_unique($subject_ids);
            }
            //$class['class_ids'] = $class_ids;
            $class['teacher_ids'] = $teacher_ids;
            $class['subject_ids'] = $subject_ids;
        }
        // pre($class); die;
        return ($class) ? $class : false;
    }
    function get_teachers_by_school_id($document) {
        $this->db->where('teachers.schoolId', $document['school_id']);
        $this->db->join('users', 'users.id = teachers.userId');
        $teachers = $this->db->get('teachers')->result_array();
        return $teachers;
    }

    function get_classes_by_school_id($document) {
        $this->db->where('classes.schoolId', $document['school_id']);
        $classes = $this->db->get('classes')->result_array();
        if ($classes) {
            foreach ($classes as $class) {
                //$class_id[] = $class['id'];
                $final_students = array();
                $class_details = $this->get_class_details_by_class_id(array("classId" => $class['id']));
                if (!empty($class_details['students'])) {
                    foreach ($class_details['students'] as $stu) {// pre($stu);
                        $final_students[] = array("class_id" => $class['id'], "class_name" => $class['name'], "student_id" => $stu['student_id'], "student_name" => $stu['student_name'], "studentImage" => $stu['studentImage'], "parent_id" => $stu['parent_id'], "email" => $stu['email'], "parent_name" => $stu['parent_name'], "profileImage" => $stu['profileImage'], "father_name" => $stu['fatherName'], "mother_name" => $stu['motherName'], "father_phone" => $stu['parent_phone'], "student_phone" => $stu['student_phone'], "student_address" => $stu['address'], "deviceType" => $stu['deviceType'], "deviceToken" => $stu['deviceToken'], "routeName" => $stu['routeName']);
                    }
                }
                $final_classes[] = array("id" => $class['id'], "name" => $class['name'], "students" => $final_students);
            }
        }//pre($final_classes); die;
        return $final_classes;
    }

    function get_school_details_by_school_id($document) {
        $this->db->where('schools.id', $document['school_id']);
        $school = $this->db->get('schools')->row_array();
        if ($school) {
            $students = array();
            $school['admin'] = $this->get_admin_by_school_id(array("schoolId" => $document['school_id']));
            $school['teachers'] = $this->get_teachers_by_school_id(array("school_id" => $document['school_id']));
            $school['classes'] = $this->get_classes_by_school_id(array("school_id" => $document['school_id']));
            $school['routes'] = $this->get_routes(array("schoolId" => $document['school_id']));
            if (!empty($school['classes'])) { //pre($school['classes']);
                $students = array();
                foreach ($school['classes'] as $class) {
                    $students = array_merge($students, $class['students']);
                }
            }
            $school['students'] = $students;
        }
        return ($school) ? $school : false;
    }

    public function get_subjects($document) {
        if ($document['name']) {
            $this->db->like('name', $document['name']);
            $result = $this->db->get('subjects')->row_array();
        }
        return (!empty($result)) ? $result : false;
    }

    public function is_phone_already($document) {
        //pre($document);
        $this->db->where('mobile', $document['phone']);
        $result = $this->db->get('users')->row_array();
        return (!empty($result)) ? $result : false;
    }

    public function is_email_or_phone_already($document) {
        $this->db->where('email', $document['email']);
        $this->db->or_where('mobile', $document['phone']);
        $result = $this->db->get('users')->row_array();
        return (!empty($result)) ? $result : false;
    }

    function isSuperAdminExist($document) {
        $this->db->where('username', $document['userName']);
        $this->db->where('email', $document['userName']);
        $result = $this->db->get('superAdmin')->row_array();
        if (!empty($result)) {
            if ($result['password'] == $document['password']) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return false;
        }
    }

    function getClassByClassId($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('classes')->row_array();
        return (!empty($result)) ? $result : false;
    }

    function filledClassesBySchoolId($id) {
        $this->db->where('schoolId', $id);
        $result = $this->db->get('classes')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function filledRoomsBySchoolId($id) {
        $this->db->where('schoolId', $id);
        $result = $this->db->get('classes')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function get_teacher($doc) {
        //pre($id);die; 
        $this->db->where('teachers.schoolId', $doc['schoolId']);
        $this->db->where('users.userType', '2');
        $this->db->like('name', $doc['name']);
        $this->db->where('email', $doc['email']);
        $this->db->join('teachers', 'users.id = teachers.userId');
        $result = $this->db->get('users')->result();
        return (!empty($result)) ? $result : false;
    }

    function getTeachersBySchoolId($id) {
        //pre($id);die; 
        $this->db->where('teachers.schoolId', $id);
        $this->db->where('users.userType', '2');
        $this->db->join('users', 'users.id = teachers.userId');
        $result = $this->db->get('teachers')->result();
        return (!empty($result)) ? $result : false;
    }

    function getTeachersBySchoolIdT($id) {
        //pre($id);die; 
        $this->db->where('schl_teachers.schoolId', $id);
        $this->db->where('userType', '2');
        $this->db->join('users', 'users.id = teachers.userId');
        $result = $this->db->get('schl_teachers')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function isRegNoExist($regno, $schoolId) {
        $this->db->where('regno', $regno);
        $this->db->where('schoolId', $schoolId);
        $result = $this->db->get('students')->row_array();
        return (!empty($result)) ? $result : false;
    }

    function getSchoolByAdmin($document) {
        $this->db->select('schools.name as schoolName,logo');
        $this->db->where('userId', $document['id']);
        $this->db->join('schools', 'admins.schoolId=schools.id');
        $result = $this->db->get('admins')->result_array();
        return (!empty($result)) ? $result[0]['schoolName'] : false;
    }

    function getTeachersList($document) {
        $this->db->select('users.id as id,users.name as name,users.dob as dob,users.email as email,users.mobile as mobile,users.userType as userType');
        $this->db->where('admins.userId', $document);
        $this->db->join('teachers', 'teachers.schoolId=admins.schoolId');
        $this->db->join('users', 'users.id=teachers.userId');
        $this->db->order_by('teachers.id', 'desc');
        $result = $this->db->get('admins')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function getDriverList($document) {
        $this->db->order_by('driver.id', 'desc');
        $this->db->select('users.id as id,users.name as name,users.dob as dob,users.email as email,users.mobile as mobile,users.userType as userType');
        $this->db->where('driver.schoolId', $document);
        $this->db->join('users', 'users.id=driver.userId');
        $result = $this->db->get('driver')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function getClassTeachersList($document) {
        //pre($document); die();
        $this->db->select('users.id as id,users.name as name,users.dob as dob,users.email as email,users.mobile as mobile,users.userType as userType');
        $this->db->where('admins.userId', $document);
        $this->db->join('teachers', 'teachers.schoolId=admins.schoolId');
        $this->db->join('classTeachers', 'classTeachers.teacherId=teachers.id');
        $this->db->join('users', 'users.id=teachers.userId');
        $result = $this->db->get('admins')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function getParentList($document) {
        //pre($document); die();
        $this->db->order_by('parents.id', 'desc');
        $this->db->select('users.id as id,users.name as name,users.dob as dob,users.email as email,users.mobile as mobile,users.userType as userType,students.name as Student');
        $this->db->where('admins.userId', $document);
        $this->db->join('students', 'students.schoolId=admins.schoolId');
        $this->db->join('parents', 'parents.studentId=students.id');
        $this->db->join('users', 'users.id=parents.userId');
        $result = $this->db->get('admins')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function get_parent($document) {
        //$this->db->select('');
        if (isset($document['studentId']) and $document['studentId'] != "") {
            $this->db->where('parents.studentId', $document['studentId']);
        }
        $result = $this->db->get('parents')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function getStudentsList($schoolId) {
        $this->db->select('users.id as user_id,users.deviceToken,students.id as id,students.name as name,students.regno as regno,students.dob as dob,students.email as email,students.phone as phone,students.classId as classId,students.fatherName as fatherName,students.motherName as motherName,studentImage');
        $this->db->where('students.schoolId', $schoolId);
        $this->db->join('parents', 'parents.studentId=students.id');
        $this->db->join('users', 'users.id=parents.userId');
        $this->db->order_by('students.id', 'desc');
        $result = $this->db->get('students')->result_array(); //echo "1";pre($result);
        foreach ($result as $res) {
            $className = $this->getClassName($res['classId']);
            $res['className'] = $className;
            $final[] = $res;
        }return (!empty($result)) ? $final : false;
    }

    function get_students_by_class($documents) {
        $this->db->select('users.id as user_id,users.deviceToken,students.id as id,students.name as name,students.regno as regno,students.dob as dob,students.email as email,students.phone as phone,students.classId as classId,students.fatherName as fatherName,students.motherName as motherName,studentImage');
        $this->db->where('students.classId', $documents['class_id']);
        $this->db->join('parents', 'parents.studentId=students.id');
        $this->db->join('users', 'users.id=parents.userId');
        $this->db->order_by('students.id', 'desc');
        $result = $this->db->get('students')->result_array(); //echo "1";pre($result);
        return $result;
    }

    public function isSubjectExist($subjectName, $schoolId) {
        $this->db->where('name', $subjectName);
        $this->db->where('schoolId', $schoolId);
        $result = $this->db->get('subjects')->row_array();
        //pre($result); die();
        return (!empty($result)) ? $result : false;
    }

    public function isClassExist($className, $schoolId) {
        $this->db->where('name', $className);
        $this->db->where('schoolId', $schoolId);
        $result = $this->db->get('classes')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function isSameTimeExist($document) {
        $start = $document['startTime'];
        $end = $document['endTime'];
        if (!empty($start == $end)) {
            return 1;
        } else {
            return false;
        }
    }

    function isStartTimeMoreThenEndTime($document) {
        $start = $document['startTime'];
        $end = $document['endTime'];
        $start2 = explode(':', $start);
        $end2 = explode(':', $end);
        if (!empty($start2 >= $end2)) {
            if (!empty($start2 > $end2)) {
                return 1;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function isStartTimeMoreThenEndTimeForTimetable($start, $end) {
        if (!empty($start > $end)) {
            return 1;
        } else {
            return false;
        }
    }

    function IsSameTimeMoreThenEndTimeForTimetable($start, $end) {
        if (!empty($start == $end)) {
            return 1;
        } else {
            return false;
        }
    }

    function isSameTimeTableExist($document) {
        $start = $document['startTime'];
        $end = $document['endTime'];
        if (!empty($start > $end)) {
            return 1;
        } else {
            return false;
        }
    }

    function isSameDateExist($document) {
        $start = $document['startDate'];
        $end = $document['endDate'];
        $d1 = date("d", strtotime($start));
        $d2 = date("d", strtotime($end));
        if ($d1 > $d2) {
            return 1;
        } else {
            return false;
        }
    }

    function isCalEventExist($document, $schoolId) {
        $this->db->where('date', $document['date']);
        $this->db->where('schoolId', $schoolId);
        if (!empty($document['class_Id']) and $document['class_Id'] != 0) {
            $this->db->where('class_Id', $document['class_Id']);
        }
        $result = $this->db->get('calender')->row_array();
        return (!empty($result)) ? $result : false;
    }

    function isEventExist($document, $schoolId) {
        $this->db->where('startDate', $document['startDate']);
        $this->db->where('endDate', $document['endDate']);
        $this->db->where('schoolId', $schoolId);

        $result = $this->db->get('schl_events')->row_array();
        echo $this->db->last_query();
        die();
        return (!empty($result)) ? $result : false;
    }

    function isTimeTableExist($insertData, $total) {
        for ($i = 0; $i < $total; $i++) {
            $this->db->where('startTime', $insertData[$i]['startTime']);
            $this->db->where('endTime', $insertData[$i]['endTime']);
            $this->db->where('teacherId', $insertData[$i]['teacherId']);
            $result = $this->db->get('schl_lecture')->result_array();
        }
        return (!empty($result)) ? $result : false;
    }

    public function isRoomExist($room, $schoolId) {
        $this->db->where('roomNo', $room);
        $this->db->where('schoolId', $schoolId);
        $result = $this->db->get('classes')->row_array();
        return (!empty($result)) ? $result : false;
    }

    function UpdateClassTeacher($document) {
        $this->db->where('classId', $document['classId']);
        $query_num = $this->db->get('classTeachers')->num_rows();
        if (!empty($query_num)) {
            $this->db->where('classId', $document['classId']);
            $result = $this->db->update('classTeachers', $document);
            return 2;
        } else {
            $result = $this->db->insert('classTeachers', $document);
            return 1;
        }
    }

    function UpdateTimeTable($document) {
        $this->db->where('startTime', $document['startTime']);
        $this->db->where('endTime', $document['endTime']);
        $this->db->where('classId', $document['classId']);
        $this->db->where('subjectId', $document['subjectId']);
        $this->db->where('teacherId', $document['teacherId']);
        $this->db->where('day', $document['day']);
        $this->db->where('schoolId', $document['schoolId']);
        $query_num = $this->db->get('schl_lecture')->num_rows();
        if (!empty($query_num)) {
            $this->db->where('classId', $document['classId']);
            $this->db->where('schoolId', $document['schoolId']);
            $result = $this->db->update('schl_lecture', $document);
            return $result;
        } else {
            $result = $this->db->insert('schl_lecture', $document);
            return $result;
        }
    }

    function editTimeTable($document, $id) {
        $this->db->where('id', $id);
        $result = $this->db->update('schl_lecture', $document);
        return $result;
    }

    public function isSchoolExist($document) {
        $this->db->where('name', $document['name']);
        $this->db->where('email', $document['email']);
        $this->db->where('phone', $document['phone']);
        $result = $this->db->get('schl_schools')->result_array();
        return (!empty($result)) ? $result : false;
    }

    public function isSchoolNameExist($document) {
        $this->db->where('name', $document['name']);
        $this->db->where('email', $document['email']);
        $result = $this->db->get('schl_schools')->row_array();
        return (!empty($result)) ? $result : false;
    }

    public function isStudentExist($document, $schoolId) {
        $this->db->where('schoolId', $schoolId);
        $this->db->where('email', $document['email']);
        $result = $this->db->get('students')->row_array();
        return (!empty($result)) ? $result : false;
    }

    public function isStudentExistPhone($document, $schoolId) {
        $this->db->select('students.id as id,students.email as email,students.phone as mobile');
        $this->db->where('schoolId', $schoolId);
        $this->db->where('phone', $document['phone']);
        $result = $this->db->get('students')->row_array();
        if ($result) {
            if ($result['fatherName'] == $document['fatherName']) {
                return false;
            } else {
                return $result;
            }
        }
    }

    public function isTeacheraExist($document, $schoolId) {
        $this->db->select('users.email as email,users.userType as userType,users.mobile as mobile');
        $this->db->where('schoolId', $schoolId);
        $this->db->join('users', 'users.id=teachers.userId');
        $result = $this->db->get('teachers')->result_array();
        $data = array();
        $data['email'] = $document['email'];
        $data['userType'] = $document['userType'];
        $data['mobile'] = $document['mobile'];
        $a = in_array($data, $result);
        return (!empty($a)) ? $a : false;
    }

    public function isTeacheraEmailExist($document, $schoolId) {
        $this->db->select('users.email as email,users.userType as userType');
        $this->db->where('schoolId', $schoolId);
        $this->db->join('users', 'users.id=teachers.userId');
        $result = $this->db->get('teachers')->result_array();
        $data = array();
        $data['email'] = $document['email'];
        $data['userType'] = $document['userType'];
        $a = in_array($data, $result);
        return (!empty($a)) ? $a : false;
    }

    public function isTeacheraPhoneExist($document, $schoolId) {
        $this->db->select('users.userType as userType,users.mobile as mobile');
        $this->db->where('schoolId', $schoolId);
        $this->db->join('users', 'users.id=teachers.userId');
        $result = $this->db->get('teachers')->result_array();
        $data = array();
        $data['userType'] = $document['userType'];
        $data['mobile'] = $document['mobile'];
        $a = in_array($data, $result);
        return (!empty($a)) ? $a : false;
    }

    public function isTeacheraPhoneExist1($document, $schoolId) {
        $this->db->select('users.userType as userType,users.mobile as mobile');
        $this->db->where('schoolId', $schoolId);
        $this->db->join('users', 'users.id=teachers.userId');
        $result = $this->db->get('teachers')->result_array();
        $data = array();
        $data['userType'] = $document['userType'];
        $data['mobile'] = $document['mobile'];
        $a = in_array($data, $result);
        return (!empty($a)) ? $a : false;
    }

    public function isEmailAlreadyExist($doc) {
        $this->db->where('email', $doc['email']);
        $this->db->where('userType', $doc['userType']);
        $result = $this->db->get('users')->row_array();
        if (!$result) {
            $this->db->where('email', $doc['email']);
            $result = $this->db->get('students')->row_array();
        }
        return !empty($result) ? $result : array();
    }

    public function isPhoneAlreadyExist($doc) {
        $this->db->where('mobile', $doc['mobile']);
        $this->db->where('email', $doc['email']);
        $this->db->where('userType', $doc['userType']);
        $result = $this->db->get('users')->row_array();
        if (!$result) {
            $this->db->where('phone', $doc['mobile']);
            $result = $this->db->get('students')->row_array();
        }
        return !empty($result) ? $result : array();
    }

    public function isAdminEmailAlreadyExist($doc) {
        $this->db->where('email', $doc['email']);
        $this->db->where('userType', $doc['userType']);
        $result = $this->db->get('users')->row_array();
        return !empty($result) ? $result : array();
    }

    public function isAdminPhoneAlreadyExist($doc) {
        $this->db->where('mobile', $doc['mobile']);
        $this->db->where('userType', $doc['userType']);
        $result = $this->db->get('users')->row_array();
        return !empty($result) ? $result : array();
    }

    public function isAdminPhoneExist($document, $schoolId) {
        $this->db->select('users.id as id,users.userType as userType,users.mobile as mobile');
        $this->db->where('admins.schoolId', $schoolId);
        $this->db->join('schl_admins', 'schl_admins.userId=users.id');
        $result = $this->db->get('users')->result_array();
        // pre($result);
        $data = array();
        $data['userType'] = $document['userType'];
        $data['mobile'] = $document['mobile'];
        // pre($data);die;
        $a = in_array($data, $result);
        //print_R($result); die;
        if (!empty($a)) {
            return $a;
        } else {
            return FALSE;
        }
    }

    public function isSchoolNameAlredyExist($document) {
        if (isset($document['schoolId'])) {
            $this->db->where('id !=', $document['schoolId']);
        }
        $this->db->where('name', $document['name']);
        $this->db->where('address', $document['address']);
        $res = $this->db->get('schl_schools')->row_array();
        return (!empty($res)) ? $res : array();
    }

    public function isSchoolEmailAlredyExist($document) {
        if (isset($document['schoolId'])) {
            $this->db->where('id !=', $document['schoolId']);
        }
        $this->db->where('email', $document['email']);
        $res = $this->db->get('schl_schools')->row_array();
        return (!empty($res)) ? $res : array();
    }

    public function isSchoolAddressAlredyExist($document) {
        if (isset($document['schoolId'])) {
            $this->db->where('id !=', $document['schoolId']);
        }
        $this->db->where('address', $document['address']);
        $res = $this->db->get('schl_schools')->row_array();
        return (!empty($res)) ? $res : array();
    }

    public function isSchoolWebsiteAlredyExist($document) {
        if (isset($document['schoolId'])) {
            $this->db->where('id !=', $document['schoolId']);
        }
        $this->db->where('website', $document['website']);
        $res = $this->db->get('schl_schools')->row_array();
        return (!empty($res)) ? $res : array();
    }

    public function isSchoolPhoneAlredyExist($document) {
        if (isset($document['schoolId'])) {
            $this->db->where('id !=', $document['schoolId']);
        }
        $this->db->where('phone', $document['phone']);
        $res = $this->db->get('schl_schools')->row_array();
        return (!empty($res)) ? $res : array();
    }

    public function isAdminEmailAlredyExist($document) {
        if (isset($document['id'])) {
            $this->db->where('id !=', $document['id']);
        }
        $this->db->where('email', $document['email']);
        $res = $this->db->get('users')->row_array();
        return (!empty($res)) ? $res : array();
    }

    public function isAdminMobileAlredyExist($document) {
        if (isset($document['id'])) {
            $this->db->where('id !=', $document['id']);
        }
        $this->db->where('mobile', $document['mobile']);
        $res = $this->db->get('users')->row_array();
        return (!empty($res)) ? $res : array();
    }

    public function isAdminExist($document, $schoolId) {
        $this->db->where('users.email', $document['email']);
        $this->db->join('schl_admins', 'schl_admins.userId=users.id');
        $result = $this->db->get('users')->row_array();
        return $result;
    }

    public function isParentsExist($document, $schoolId) {
        // pre($document);die('sir');
        $this->db->select('users.email as email,users.userType as userType,users.mobile as mobile');
        $this->db->where('schoolId', $schoolId);
        $this->db->join('parents', 'parents.studentId=students.id');
        $this->db->join('users', 'users.id=parents.userId');
        $result = $this->db->get('students')->result_array();
        $data = array();
        $data['email'] = $document['email'];
        $data['userType'] = $document['userType'];
        $data['mobile'] = $document['mobile'];
        $a = in_array($data, $result);
        if (!empty($a)) {
            return $a;
        } else {
            return FALSE;
        }
    }

    public function isParentPhoneExist($document, $schoolId) {

        $this->db->select('users.userType as userType,users.mobile as mobile');
        $this->db->where('schoolId', $schoolId);
        $this->db->join('parents', 'parents.studentId=students.id');
        $this->db->join('users', 'users.id=parents.userId');
        $result = $this->db->get('students')->result_array();
        $data = array();
        $data['userType'] = $document['userType'];
        $data['mobile'] = $document['mobile'];

        $a = in_array($data, $result);
        //print_R($result); die;
        if (!empty($a)) {
            return $a;
        } else {
            return FALSE;
        }
    }

    public function IsDriverExist($document, $schoolId) {
        //$this->db->select('users.email as email,users.mobile as mobile,users.userType as userType');
        $this->db->where('users.mobile', $document['mobile']);
        $this->db->where('driver.schoolId', $schoolId);
        $this->db->join('users', 'users.id = driver.userId');
        $result = $this->db->get('driver')->row_array();
        // pre($result);die();
        if (!empty($result)) {
            return $result;
        } else {
            $this->db->where('mobile', $document['mobile']);
            $this->db->or_where('email', $document['email']);
            $result1 = $this->db->get('users')->row_array();
            if (!empty($result1)) {
                if (isset($document['id']) and $document['id'] == $result1['id']) {
                    return false;
                }
                return $result1;
            }
            return false;
        }
    }

    public function IsDriverExist1($document) {
        $this->db->where('users.mobile', $document['mobile']);
        $this->db->or_where('users.email', $document['email']);
        //$this->db->where('schoolId', $schoolId);
        $this->db->join('users', 'users.id = driver.userId');
        $result = $this->db->get('driver')->row_array();
        //pre($document); pre($result); //die();
        if (!empty($result)) {
            if (isset($document['id']) and $document['id'] == $result['id']) {
                return false;
            }
            return $result;
        } else {
            return false;
        }
    }

    public function getWayDetails($id) {
        $this->db->where('routeId', $id);
        $result = $this->db->get('waypoints')->result_array();
        return $result;
    }

    public function deleteWay($id) {
        $this->db->where('routeId', $id);
        $result = $this->db->delete('waypoints');
        return $result;
    }

    public function deleteRoute($id) {
        $this->db->where('id', $id);
        $result = $this->db->delete('routes');
        $this->db->where('routeId', $id);
        $this->db->delete('waypoints');
        return (!empty($result)) ? $result : false;
    }

    public function delete_class($id) {
        $this->db->where('id', $id);
        $result = $this->db->delete('classes');
        return (!empty($result)) ? $result : false;
    }

    public function getRouteDetails($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('routes')->result();
        return $result;
    }

    public function get_routes($document) {
        $this->db->where('schoolId', $document['schoolId']);
        $result = $this->db->get('routes')->result_array();
        return (!empty($result)) ? $result : false;
    }

    public function getRoute($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('routes')->row_array();
        return (!empty($result)) ? $result : false;
    }

    public function getWay($id) {
        $this->db->where('routeId', $id);
        $result = $this->db->get('waypoints')->result_array();
        return $result;
    }

    public function isResgistrationExist($resgistionNo) {
        $this->db->where('regno', $resgistionNo);
        $result = $this->db->get('students')->row_array();
        return (!empty($result)) ? $result : false;
    }

    function getClassName($document) {
        $this->db->where('id', $document);
        $result = $this->db->get('classes')->row_array();
        return (!empty($result)) ? $result['name'] : false;
    }

    function getSubjectList($document) {
        $this->db->order_by('name');
        $this->db->where('schoolId', $document);
        $result = $this->db->get('subjects')->result();
        return (!empty($result)) ? $result : false;
    }

    function getSubjectListT($document) {
        $this->db->order_by('id', 'desc');
        $this->db->where('schoolId', $document);
        $result = $this->db->get('subjects')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function getClassList($document) {
        $this->db->where('schoolId', $document);
        $this->db->order_by('classes.id', 'desc');
        $result = $this->db->get('classes')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function getAdmin($schoolId) {
        $this->db->where('schoolId', $schoolId);
        $result = $this->db->get('admins')->row_array();
        return (!empty($result)) ? $result : false;
    }

    function getClassNameByClassId($cId) {
        $this->db->where('id', $cId);
        $result = $this->db->get('classes')->row_array();
        return $result;
    }

    function getClassNameByClassIdSecond($event) {

        $this->db->where('id', $event['class_Id']);
        $result = $this->db->get('classes')->row_array();
        //pre($result);die;
        return $result;
    }

    function getClassNameByClassIdHoliday($event) {
        //pre($event);die;  
        $this->db->where('id', $event['classId']);
        $result = $this->db->get('classes')->row_array();
        //pre($result);die;
        return $result;
    }

    function getSchoolEvents($id) {

        $this->db->order_by('events.id', 'desc');
        $this->db->where('admins.userId', $id);
        $this->db->join('events', 'events.schoolId=admins.schoolId');
        $result = $this->db->get('schl_admins')->result_array();
        if (!empty($result)) {
            foreach ($result as $res) {
                $className = $this->getClassNameByClassId($res['class_Id']);
                $res['className'] = $className['name'];
                $final[] = $res;
            }
        }
        if (!empty($final)) {
            return $final;
        }
    }

    function getCalenderEvents($id) {
        $this->db->order_by('calender.date', 'desc');
        $this->db->where('admins.userId', $id);
        $this->db->join('calender', 'calender.schoolId=admins.schoolId');
        $this->db->order_by('calender.id','desc');
        $result = $this->db->get('schl_admins')->result_array();
        if (!empty($result)) {
            foreach ($result as $res) {
                $className = $this->getClassNameByClassId($res['class_Id']);
                $res['className'] = $className['name'];
                $final[] = $res;
            }
        }
        if (!empty($final)) {
            return $final;
        }
    }

    function get_complaints_List($document) {
        $this->db->select('users.id as user_id,users.userType as user_type,users.name as user_name,complaints.id as id,complaints.userType as userType,complaints.subject as subject,complaints.description as description,complaints.status as status,schools.name as name,complaints.created as complaint_date');
        if (isset($document['school_id']) and ! empty($document['school_id'])) {
            $this->db->where('complaints.schoolId', $document['school_id']);
        } else {
            $this->db->where('complaints.userType', 1);
        }
        $this->db->join('schools', 'schools.id=complaints.schoolId');
        $this->db->join('users', 'users.id=complaints.userId');
        $this->db->order_by("complaints.id", "desc");
        $result = $this->db->get('complaints')->result_array(); // pre($result); die();
        if ($result) {
            foreach ($result as $res) {
                if ($res['user_type'] == 3) {
                    $this->db->select('classes.name as class_name,students.name as student_name');
                    $this->db->where('parents.userId', $res['user_id']);
                    $this->db->join('students', 'students.id=parents.studentId');
                    $this->db->join('classes', 'classes.id=students.classId');
                    $get_parents = $this->db->get('parents')->row_array(); //pre($get_parents);
                    if ($get_parents) {
                        $res['class_name'] = $get_parents['class_name'];
                        $res['student_name'] = $get_parents['student_name'];
                    }
                }
                $final[] = $res;
            }
        }
        return (!empty($result)) ? $final : false;
    }

    function getComplaintsList1() {
        $this->db->select('complaints.id as id,complaints.userType as userType,complaints.subject as subject,complaints.description as description,complaints.status as status,schools.name as name,complaints.created as complaint_date');
        $this->db->join('complaints', 'complaints.userId=admins.userId');
        $this->db->join('schools', 'schools.id=admins.schoolId');
        $result = $this->db->get('admins')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function getComplaintsList($id) {
        $this->db->select('complaints.id as id,complaints.userType as userType,complaints.subject as subject,complaints.description as description,complaints.status as status,schools.name as name,complaints.created as complaint_date');
        $this->db->where('complaints.schoolId', $id);
        $this->db->order_by("complaints.id", "desc");
        $this->db->join('schools', 'schools.id=complaints.schoolId');
        $result = $this->db->get('complaints')->result_array();
        return (!empty($result)) ? $result : false;
    }

    function addHolidays($document) {
        $result = $this->db->insert('schl_holidays', $document);
        if (!empty($result)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function isHolidayExist($dated, $schoolId, $classId, $title) {
        $this->db->where('dated', $dated);
        $this->db->where('classId', $classId);
        $this->db->where('title', $title);
        $this->db->where('schoolId', $schoolId);
        $result = $this->db->get('schl_holidays')->result_array();
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    function viewHolidays($schoolId) {
        $this->db->order_by('holidays.id', 'desc');
        $this->db->where('schoolId', $schoolId);
        $result = $this->db->get('schl_holidays')->result_array();
        if (!empty($result)) {
            foreach ($result as $res) {
                $className = $this->getClassNameByClassId($res['classId']);
                $res['className'] = $className['name'];
                $final[] = $res;
            }
        }
        if (!empty($final)) {
            return $final;
        } else {
            return $result;
        }
    }

    function editHolidays($updateArray) {
        $this->db->where('id', $updateArray['id']);
        $result = $this->db->update('schl_holidays', $updateArray);
        if (!empty($result)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function deleteHolidays($id = null) {
        $result = $this->db->delete('schl_holidays', array('id' => $id));
        return $result;
    }

    public function deleteEvent($id = null) {
        $result = $this->db->delete('schl_events', array('id' => $id));
        return $result;
    }

    public function deleteCalender($id = null) {
        $result = $this->db->delete('schl_calender', array('id' => $id));
        return $result;
    }

    public function deleteComplaints($id = null) {
        $result = $this->db->delete('schl_complaints', array('id' => $id));
        return $result;
    }

    public function deleteSubjects($id = null) {
        $result = $this->db->delete('subjects', array('id' => $id));
        return $result;
    }
/*
    public function deleteStudent($id = null) {
        $this->db->where('studentId', $id);
        $get_parents = $this->db->get('parents')->row_array();
        $deleted = $this->db->delete('schl_students', array('id' => $id));
        $deleted1 = $this->db->delete('schl_parents', array('studentId' => $id));
        $deleted2 = $this->db->delete('schl_users', array('id' => $get_parents['userId']));
        return $deleted;
    }
*/
    public function deleteTeacher($id = null) {
        $imageUrl['img'] = $this->db->select('*')->from('schl_users')->where(array('id' => $id))->get()->row();
        $path = $imageUrl['img']->profileImage;
        $location = getcwd() . '/profileImages/' . $path;
        @unlink("profileImages/thumbnails/" . $path);
        @unlink("profileImages/" . $path);
        $this->db->where(array('id' => $id));
        $result = $this->db->delete('users');
        $this->db->where(array('userId' => $id));
        $result = $this->db->delete('teachers');
        $this->db->where(array('teacherId' => $id));
        $result = $this->db->delete('classTeachers');
        $this->db->where(array('teacherId' => $id));
        $result = $this->db->delete('lecture');
        return $result;
    }

    public function deleteDriver($id = null) {
        $imageUrl['img'] = $this->db->select('*')->from('schl_users')->where(array('id' => $id))->get()->row();
        $path = $imageUrl['img']->profileImage;
        $location = getcwd() . '/profileImages/' . $path;
        @unlink("profileImages/thumbnails/" . $path);
        @unlink("profileImages/" . $path);
        $this->db->where(array('id' => $id));
        $result = $this->db->delete('schl_users');
        $this->db->where(array('userId' => $id));
        $result = $this->db->delete('schl_driver');
        return $result;
    }

    public function deleteSubject($id = null) {
        $this->db->where(array('id' => $subjectId));
        $result = $this->db->delete('subjects');
        return $result;
    }

    public function deleteParents($id = null) {
        $imageUrl['img'] = $this->db->select('*')->from('schl_users')->where(array('id' => $id))->get()->row();
        $path = $imageUrl['img']->profileImage;
        $location = getcwd() . '/profileImages/' . $path;
        @unlink("profileImages/thumbnails/" . $path);
        @unlink("profileImages/" . $path);
        $this->db->where(array('id' => $id));
        $result = $this->db->delete('schl_users');
        return $result;
    }

    public function deleteGroup($id = null) {
        $result = $this->db->delete('schl_groups', array('id' => $id));
        return $result;
    }

    public function deleteSchool($document) {
        $schoolId = $document['schoolId'];
        $this->db->where('id', $schoolId);
        $result = $this->db->delete('schools');

        //echo $result; die();
        if ($result) {
            $users = array();
            $getAdmin = $this->getAdmin($schoolId);
            if ($getAdmin) {
                $users[] = $getAdmin['userId'];
            }
            $getClassList = $this->getClassList($schoolId);
            if ($getClassList) {
                foreach ($getClassList as $class) {
                    $classes[] = $class['id'];
                    $this->delete_class($class['id']);
                }
            }
            //delete Attendence
            if (isset($classes)) {
                $this->db->where_in('classId', $classes);
                $this->db->delete('attendance');
            }
            // delete route and and waypoints
            $get_routes = $this->get_routes(array("schoolId" => $schoolId));
            if ($get_routes) {
                foreach ($get_routes as $route) {
                    $routes[] = $route['id'];
                    $this->deleteRoute($route['id']);
                }
                //$this->db->where_in('routeId',$routes);
            }
            //delete drivers
            $this->db->where('schoolId', $schoolId);
            $get_drivers = $this->db->get('driver')->result_array();
            if ($get_drivers) {
                foreach ($get_drivers as $driver) {
                    $users[] = $driver['userId'];
                    $this->db->where('id', $driver['id']);
                    $this->db->delete('driver');
                }
            }
            //delete students and parents
            $getStudentsList = $this->getStudentsList($schoolId);
            if ($getStudentsList) {
                foreach ($getStudentsList as $student) {
                    $students[] = $student['id'];
                    $this->deleteStudent($student['id']);
                    $this->db->where('studentId', $student['id']);
                    $get_parents = $this->db->get('parents')->result_array();
                    if ($get_parents) {
                        foreach ($get_parents as $parent) {
                            $users[] = $parent['userId'];
                            $this->db->where('id', $parent['id']);
                            $this->db->delete('parents');
                        }
                    }
                }
            }
            //delete teachers
            $this->db->where('schoolId', $schoolId);
            $get_teachers = $this->db->get('teachers')->result_array();
            if ($get_teachers) {
                foreach ($get_teachers as $teacher) {
                    $users[] = $teacher['userId'];
                    $this->db->where('id', $teacher['id']);
                    $this->db->delete('teachers');
                }
            }
            if (!empty($users)) {
                $this->db->where_in('id', $users);
                $this->db->delete('users');
            }
            $tables_str = "admins,calender,classTeachers,complaints,holidays,events,lecture,subjects";
            $tables = explode(',', $tables_str);
            foreach ($tables as $table) {
                $this->db->where('schoolId', $schoolId);
                $result = $this->db->delete($table);
            }
        }
        return true;
    }

    function allAdmins() {
        $this->db->where('userType', 1);
        $this->db->order_by('users.id', 'desc');
        $this->db->join('admins', 'admins.userId=users.id');
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    function addEvent($insertArray) {
        $result = $this->db->insert('schl_events', $insertArray);
        return $result;
    }

    function insert_sms_recod($records) {
        $result = $this->db->insert('sms_records', $records);
        return $result;
    }

    function addCalEvent($insertArray) {
        //pre($insertArray);die;

        $result = $this->db->insert('schl_calender', $insertArray);
        return $result;
    }

    function editCalEvent($updateArray) {

        $this->db->where('id', $updateArray['id']);
        $result = $this->db->update('schl_calender', $updateArray);
        return $result;
    }

    function editEvent($updateArray) {

        $this->db->where('id', $updateArray['id']);
        $result = $this->db->update('schl_events', $updateArray);
        return $result;
    }

    function getStudentIdByRegno($regno, $schoolId) {
        //$this->db->where('userType', 3);
        $this->db->where('regno', $regno);
        $this->db->where('schoolId', $schoolId);
        $result = $this->db->get('students')->row_array();
        // pre($result); 
        // echo $result['id']; die();
        return $result;
    }

    function getcalender($schoolId) {
        $i = 0;
        $final = $all_dates = array();
        $this->db->order_by('date', 'desc');
        $this->db->where('schoolId', $schoolId);
        $result = $this->db->get('schl_calender')->result_array();
        if ($result) {
            foreach ($result as $res) {
                if ($res['eventType'] == 1) {
                    $res['description'] = '<big >School Event</big><br><small>' . $res['description'] . '</small>';
                } else if ($res['eventType'] == 2) {
                    $class = $this->getClassByClassId($res['class_Id']);
                    $res['description'] = '<big><red>' . $class['name'] . ' class </red></big><br><small>' . $res['description'] . '</small>';
                    //$res['description'] = $res['description'].'<br><strong style="color:blue">'.$class['name'].'</strong>';
                } elseif ($res['eventType'] == 3) {
                    $res['description'] = '<big>Teachers Event</big><br><small>' . $res['description'] . '</small>';
                }
                $dates = $this->createDateRangeArray($res['date'], $res['end_date']);
                foreach ($dates as $date) {
                    $res['date'] = $date;
                    if (in_array($date, $all_dates)) {
                        $final[$keys[$date]]['description'] = $final[$keys[$date]]['description'] . ', ' . $res['description'];
                    } else {
                        $final[] = $res;
                        $all_dates[] = $date;
                        $keys[$date] = $i;
                        $i++;
                    }
                }
            }
        }
        //pre(array_values($final));

        return $final;
    }

    /*
      if($result){
      foreach($result as $res){
      $dates = $this->createDateRangeArray($res['date'],$res['end_date']);
      foreach($dates as $date){
      $res['date'] = $date;
      if(in_array($date, $all_dates)){
      $final[$keys[$date]]['description'] = $final[$keys[$date]]['description'].','.$res['description'];
      }else{
      $final[]        = $res;
      $all_dates[]    = $date;
      $keys[$date]    = $i;
      $i++;
      }
      }
      }
      foreach($final as $f){ //pre($f);die();
      $r[] = $f;
      }
      pre($r);
      }
      pre(array_values($final));
     */
/*
    function ageByDOB($date) {
        list($year, $month, $day) = explode("-", $date);
        $year_diff = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff = date("d") - $day;
        if ($day_diff < 0 || $month_diff < 0)
            $year_diff--;
        //echo $year_diff; die();
        return $year_diff;
    }
*/
    function addGroup($insertArray) {

        $result = $this->db->insert('schl_groups', $insertArray);
        return $result;
    }

    function editGroup($updateArray) {

        $this->db->where('id', $updateArray['id']);
        $result = $this->db->update('schl_groups', $updateArray);
        return $result;
    }

    function viewGroup() {
        $result = $this->db->get('schl_groups')->result_array();
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
        //pre($final); die();
    }

    function addRoute($insertArray) {
        // pre($insertArray); die();
        $result = $this->db->insert('schl_routes', $insertArray);
        $id = $this->db->insert_id();
        //pre($result); die();
        return $id;
    }

    function editRoute($insertArray, $id) {
        // pre($insertArray); die();
        $this->db->where('id', $id);
        $result = $this->db->update('schl_routes', $insertArray);
        $id = $this->db->insert_id();
        //pre($result); die();
        return $id;
    }

    function editWay($insertArray, $id, $ids) {
        if ($ids == 0) {
            $this->db->where('routeId', $id);
            $result = $this->db->delete('schl_waypoints');
        }
        $result = $this->db->insert('schl_waypoints', $insertArray);
        $id = $this->db->insert_id();
        return $id;
    }

    function getAllRoute() {
        $result = $this->db->get('schl_routes')->result_array();
        return $result;
    }

    function getStartPoint($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('schl_routes')->row_array();
        if (!empty($result)) {
            return $result['startPoint'];
        } else {
            return false;
        }
    }

    function getEndPoint($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('schl_routes')->row_array();
        if (!empty($result)) {
            return $result['endPoint'];
        } else {
            return false;
        }
    }

    function getWayPoint($id) {
        $result = $this->db->select('waypoints')->get_where('schl_waypoints', array('routeId' => $id))->result();
        return $result;
    }

    public function mapView($routeId) {
        $data['start'] = $this->getStartPoint($routeId);
        $data['end'] = $this->getEndPoint($routeId);
        $data['way'] = $this->getWayPoint($routeId);
        //pre($data); die();
        if (!empty($data)) {
            return $data;
        } else {
            return false;
        }
    }

    function add_contact($array) {
        // pre($insertArray); die();
        if (isset($array['id']) and ! empty($array['id'])) {
            $this->db->where('id', $array['id']);
            $result = $this->db->update('contacts', $array);
        } else {
            $result = $this->db->insert('contacts', $array);
        }
        return true;
    }

    function contacts_list($array) {
        //pre($array);die();
        if (isset($array['schoolId']) and ! empty($array['schoolId'])) {
            $this->db->where('schoolId', $array['schoolId']);
        }
        $result = $this->db->get('contacts')->result_array();
        return ($result) ? $result : false;
    }

    function add_notice($array) {
        //pre($array); die();
        if (isset($array['id']) and ! empty($array['id'])) {
            $this->db->where('id', $array['id']);
            $result = $this->db->update('notices', $array);
        } else {
            $result = $this->db->insert('notices', $array);
            $array['id'] = $this->db->insert_id();
        }

        return $array;
    }

    function notices($array) {
        //pre($array);
        if (isset($array['schoolId']) and ! empty($array['schoolId'])) {
            $this->db->where('schoolId', $array['schoolId']);
        }
        $result = $this->db->get('notices')->result_array();
        if ($result) {
            foreach ($result as $res) {
                $res['classes_for'] = '';
                if ($res['notice_type'] == 4) {
                    $res['classes_for'] = "All Classes";
                } else {
                    //pre($res['classes']);
                    $classes = explode(',', $res['classes']);
                    //pre($classes);// die;
                    foreach ($classes as $class) {
                        $class_res = $this->getClassByClassId($class);
                        if (empty($res['classes_for'])) {
                            $res['classes_for'] = $class_res['name'];
                        } else {
                            $res['classes_for'] = $res['classes_for'] . ', ' . $class_res['name'];
                        }
                    }
                }
                $final[] = $res;
            }
        }
        //echo $this->db->last_query();
        return ($result) ? $final : false;
    }

    function create_album($array) {
        if (isset($array['id']) and ! empty($array['id'])) {
            $this->db->where('id', $array['id']);
            $result = $this->db->update('albums', $array);
        } else {
            $result = $this->db->insert('albums', array("schoolId" => $array['schoolId'], "name" => $array['name'], "created" => date('Y-m-d H:i:s')));
            $id = $this->db->insert_id();
            if ($array['images']) {
                foreach ($array['images'] as $img) {
                    $this->db->insert('album_photos', array("album_id" => $id, "name" => $img, "created" => date('Y-m-d H:i:s')));
                }
            }
        }
        return true;
    }

    function upload_photos($array) {
        foreach ($array['images'] as $photo) {
            $result = $this->db->insert('album_photos', array("album_id" => $array['album_id'], "name" => $photo));
        }
        return true;
    }

    function photos($array) {
        if (isset($array['album_id']) and ! empty($array['album_id'])) {
            $this->db->where('album_id', $array['album_id']);
        }
        if (isset($array['id']) and ! empty($array['id'])) {
            $this->db->where('id', $array['id']);
        }
        $this->db->order_by('id', 'desc');
        $result = $this->db->get('album_photos')->result_array();
        return ($result) ? $result : false;
    }

    function albums($array) {
        if (isset($array['schoolId']) and ! empty($array['schoolId'])) {
            $this->db->where('schoolId', $array['schoolId']);
        }
        $this->db->order_by('id', 'desc');
        //$this->db->join('album_photos','album_photos.album_id=albums.id','left');
        $results = $this->db->get('albums')->result_array();
        if ($results) {
            foreach ($results as $result) {
                $result['photos'] = $this->photos(array("album_id" => $result['id']));
                $final[] = $result;
            }
        }
        return ($results) ? $final : array();
    }

    public function delete_album($album) {
        $photos = $this->photos(array("album_id" => $album['id']));
        if ($photos) {
            foreach ($photos as $photo) {
                $image = getcwd() . "/uploads/gallery/" . $photo['name'];
                if (file_exists($image)) {
                    unlink($image);
                }
            }
            $result = $this->db->delete('album_photos', array('album_id' => $album['id']));
        }
        $result = $this->db->delete('albums', array('id' => $album['id']));
        return true;
    }

    public function delete_photo($photo) {
        $photos = $this->photos(array("id" => $photo['id']));
        if ($photos) {
            $image = getcwd() . "/uploads/gallery/" . $photos[0]['name'];
            if (file_exists($image)) {
                unlink($image);
            }
        }
        $result = $this->db->delete('album_photos', array('id' => $photo['id']));
        return $photos;
    }

    public function get_student($data) {
        $this->db->select('students.*,users.password,users.id as p_id');
        $this->db->where('students.id', $data['student_id']);
        $this->db->join('parents', 'parents.studentId=students.id');
        $this->db->join('users', 'users.id=parents.userId');
        $student_details = $this->db->get('students')->row();
        return $student_details;
    }

}
