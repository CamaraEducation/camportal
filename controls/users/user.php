<?php
class UsersControl{
    public static function get($id){
        $sql = "SELECT * FROM users WHERE id='$id'";
        return mysqli_fetch_assoc(mysqli_query(conn(), $sql));
    }

    public static function fetch_staff(){
        $sql = "SELECT * FROM users WHERE user_role<'6'";
        return mysqli_fetch_all(mysqli_query(conn(), $sql), MYSQLI_ASSOC);
    }

    public static function fetch_student(){
        $sql = "SELECT * FROM users WHERE user_role='6'";
        return mysqli_fetch_all(mysqli_query(conn(), $sql), MYSQLI_ASSOC);
    }

    public static function create(){
        foreach($_POST as $key => $value){ $$key = mysqli_real_escape_string(conn(), $value); } $pass = md5($pass);
        $sql = "INSERT INTO users (username, first_name, last_name, user_role, user_pass, user_gender, user_class, user_age) VALUES ('$username', '$fname', '$lname', '$role', '$pass', '$gender', '$class', '$yob')";
        if(mysqli_query(conn(), $sql)){
            header("Location: /");
        }
    }

    public static function delete($id){
        $sql = "DELETE FROM users WHERE id='$id'";
        if(mysqli_query(conn(), $sql)){
            $query[0] = "DELETE FROM page_visit WHERE visitor='$id'";
            $query[1] = "DELETE FROM app_visits WHERE visitor='$id'";
            $query[2] = "DELETE FROM video_views WHERE user_id='$id'";
            $query[3] = "DELETE FROM doc_views WHERE user_id='$id'";

            foreach($query as $q){
                mysqli_query(conn(), $q);
            }
        }
    }

    public static function fetch_user_data($id){
        $sql = "SELECT * FROM users WHERE id='$id'";
        return mysqli_fetch_assoc(mysqli_query(conn(), $sql));
    }

    public static function fetch_data($id, $value, $type){
        if ($type == 'data'){ $user = UsersControl::fetch_user_data($id);}
            else{ $user = UsersControl::get($id); 
        }
        return $user[$value];
    }
}
?>
