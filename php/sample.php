<?php
require_once('include/serv_cfg.php');
include("include/excelwriter.inc.php");
$result = false;
if (!session_id()) {
    session_start();
}

$array = array(
    $_SESSION['MM_Memberid'],
    $member_id
);
$check = check_numeric($array);

if (($member_id != $_SESSION['MM_Memberid']) || ($check != true) || (!isset($_SESSION['MM_Memberid']))) {
    die("Unauthorised download, Supplied wrong parameters.");
}

$get_user = get_user_info($member_id);

if ($get_user['success'] != true) {
    die("Unauthorised download, Supplied wrong parameters.");
}
$user_doc_folder = $get_user['user_doc_folder'];

switch ($request) {
    
    case "video_access":
        
        $query  = get_view_download_count($member_id);
        $videos = $query['videos'];
        
        $file_name = "VBLC_Video_Access_Count-" . $current_date_time . ".xls";
        
        $excel = new ExcelWriter($user_doc_folder . "/Report.xls");
        
        if ($excel == false) {
            echo $excel->error;
        }
        $excel->writeRow();
        $excel->writeCol("Video Title");
        $excel->writeCol("View Count");
        $excel->writeCol("Download Count");
        $excel->writeCol("Upload Date");
        
        foreach ($videos as $video) {
            $video_title    = $video['alias'];
            $view_count     = $video['instant_view_counter'];
            $download_count = $video['no_download'];
            $date_upload    = $video['date_upload'];
            
            $excel->writeRow();
            $excel->writeCol($video_title);
            $excel->writeCol($view_count);
            $excel->writeCol($download_count);
            $excel->writeCol($date_upload);
        }
        
        break;
    
    case "community":
        
        $query       = get_owned_communities($member_id);
        $communities = $query['communities'];
        
        $file_name = "VBLC_Community_Count-" . $current_date_time . ".xls";
        
        $excel = new ExcelWriter($user_doc_folder . "/Report.xls");
        
        if ($excel == false) {
            echo $excel->error;
        }
        $excel->writeRow();
        $excel->writeCol("Community Name");
        $excel->writeCol("Community Member");
        $excel->writeCol("Email");
        $excel->writeCol("Role");
        $excel->writeCol("Activated");
        $excel->writeCol("Quit");
        
        foreach ($communities as $community) {
            $community_name   = $community['community_name'];
            $community_id     = $community['community_id'];
            $members          = get_community_members($community_id);
            $originating_user = $members['originating_user'];
            
            foreach ($members['accepting_users'] as $member) {
                $name           = $member['name'];
                $e_mail         = $member['email'];
                $activate       = $member['activate'];
                $quit           = $member['quit'];
                $role           = $member['role'];
                $accepting_user = $member['member_id'];
                
                if ($activate == 1) {
                    $activate = "yes";
                } else {
                    $activate = "no";
                }
                if ($quit == 1) {
                    $activate = "yes";
                } else {
                    $quit = "no";
                }
                if ($accepting_user == $originating_user) {
                    $role = "Host";
                } else {
                    if ($role == 1) {
                        $role = "Assistant";
                    } else {
                        $role = "Member";
                    }
                }
                $excel->writeRow();
                $excel->writeCol($community_name);
                $excel->writeCol($name);
                $excel->writeCol($e_mail);
                $excel->writeCol($role);
                $excel->writeCol($activate);
                $excel->writeCol($quit);
            }
            
        }
        
        break;
    
    case "community_video":
        
        $query = get_joined_communities($member_id);
        
        $communities = $query['communities'];
        
        $file_name = "VBLC_Community_Video_Access_Count-" . $current_date_time . ".xls";
        
        $excel = new ExcelWriter($user_doc_folder . "/Report.xls");
        
        if ($excel == false) {
            echo $excel->error;
        }
        $excel->writeRow();
        $excel->writeCol("Community Name");
        $excel->writeCol("Role");
        $excel->writeCol("Video Title");
        $excel->writeCol("View Count");
        $excel->writeCol("Download Count");
        
        foreach ($communities as $community) {
            $community_id              = $community['community_id'];
            $get_community_description = get_community_description_info($community_id);
            $community_name            = $get_community_description['community_descriptions']['community_name'];
            $is_host                   = is_host($community_id, $member_id);
            $is_assistant_host         = is_assistant_host($community_id, $member_id);
            if ($is_host['success'] == true) {
                $role = "Host";
            } else if ($is_assistant_host['success'] == true) {
                $role = "Assistant Host";
            } else {
                $role = "General Member";
            }
            
            $get_community_videos = get_community_videos($community_id, $member_id);
            $entrie_videos        = $get_community_videos['videos'];
            
            $mode        = "community";
            $videos      = normalize_videos_array($entrie_videos, $mode);
            $video_count = count($videos);
            if ($video_count > 0) {
                
                foreach ($videos as $video_id => $alias) {
                    $get_video_access_counter = get_video_access_counter($video_id);
                    $instant_view_counter     = $get_video_access_counter['instant_view_counter'];
                    $no_download              = $get_video_access_counter['no_download'];
                    
                    $excel->writeRow();
                    $excel->writeCol($community_name);
                    $excel->writeCol($role);
                    $excel->writeCol($alias);
                    $excel->writeCol($instant_view_counter);
                    $excel->writeCol($no_download);
                }
            }
        }
        break;
    
    default:
        die("Unauthorised download, Supplied wrong parameters.");
}

$excel->close();
header("Content-Type: application/vnd.ms-excel");
header('Content-disposition: attachment; filename=' . $file_name);
readfile($user_doc_folder . "/Report.xls");
exit();
?>
