<?php 
/**
 * 編輯會員資料/會員密碼接口
 * 接 components / MemCentre.vue
*/

//header設置
require_once("./headerUse.php");
//驗證登入
require_once("./verifyFrontLogin.php");
//DB連線設置
require_once("./connect_cgd103g1.php");

//參數處理
$mem_id = empty( $_GET["mem_id"] ) ? ( $_POST["mem_id"] ?? "" ) : $_GET["mem_id"];
$userid = !isset($getUser["mem_id"])?$mem_id:$getUser["mem_id"];
$type = empty( $_GET["type"] ) ? ( $_POST["type"] ?? "" ) : $_GET["type"]; //請求類型(1:編輯會員資料,2:重設會員密碼)

$useraccount = !isset($getUser["mem_account"])?$mem_id:$getUser["mem_account"];
$mem_account = empty( $_GET["mem_idmem_account"] ) ? ( $_POST["mem_account"] ?? "" ) : $_GET["mem_account"];

$resDate = [
    "status" => 0,  //狀態(0:失敗,1:成功)
    "msg" => "",
];

switch ( $type ) {
    case 1:
    //1:編輯會員資料
        $setSql = "";
        if ( isset($_POST["mem_name"]) && !empty($_POST["mem_name"]) ) $setSql .= " mem_name = '{$_POST["mem_name"]}' ";
        if ( isset($_POST["mem_account"]) && !empty($_POST["mem_account"]) ) $setSql .= ( empty($setSql) ? "" : "," )." mem_account = '{$_POST["mem_account"]}' ";
        if ( isset($_POST["mem_mob"]) && !empty($_POST["mem_mob"]) ) $setSql .= ( empty($setSql) ? "" : "," )." mem_mob = '{$_POST["mem_mob"]}' ";
        if ( isset($_POST["mem_add"]) && !empty($_POST["mem_add"]) ) $setSql .= ( empty($setSql) ? "" : "," )." mem_add = '{$_POST["mem_add"]}' ";

        // $tmpSql = "UPDATE tibamefe_cgd103g1.member SET mem_name = 'aaa',mem_account='mmmm' WHERE mem_id = {$id} ";

        if ( empty( $setSql ) ) {
            $resDate["msg"] = "沒有數據需要更新";
            echo json_encode( $resDate );
            return true;
        }
        if ($useraccount != $mem_account){
            $resDate["msg"] = "此信箱已有人使用";
            echo json_encode( $resDate );
            return true;
        }

        $upSql = "UPDATE tibamefe_cgd103g1.member SET {$setSql} WHERE mem_id = {$userid} "; //針對某會員 id 修改
        try { 
            $res = $pdo->query( $upSql );
            if( $res ){
                $resDate["status"] = 1;
                $resDate["msg"] = 'sucess';
            }
            echo json_encode( $resDate );
            return true;
        } catch ( Exception $e ) {
            $resDate["msg"] = $e->getMessage();
            echo json_encode( $resDate );
            return true;
        }
        break;
    case 2:
    //2:重設會員密碼
        if ( ( !isset($_POST["mem_oldpsw"]) || empty($_POST["mem_oldpsw"]) ) || ( !isset($_POST["mem_newpsw"]) || empty($_POST["mem_newpsw"]) ) ) {
            $resDate["msg"] = "欄位不可為空!";
            echo json_encode( $resDate );
            return true;
        }
        
        // 搜索一次自身,並確認原密碼有無錯誤
        $sql = "SELECT * FROM tibamefe_cgd103g1.member WHERE mem_id = {$userid}";
        $res = $pdo->query($sql);
        $getMember = $res->fetch(PDO::FETCH_ASSOC);
        $user_oldpws = $getMember["mem_psw"]??'';
        if ( empty($user_oldpws) ) {
            $resDate["msg"] = '查無此人';
            echo json_encode( $resDate );
            return true;
        }
        if ( $user_oldpws != $_POST["mem_oldpsw"] ) {
            $resDate["msg"] = '原密碼錯誤!';
            echo json_encode( $resDate );
            return true;
        }

        // 沒有錯誤,更新新密碼
        $upSql = "UPDATE tibamefe_cgd103g1.member SET mem_psw = '{$_POST["mem_newpsw"]}' WHERE mem_id = {$userid} "; //針對某會員 id 修改
        try { 
            $res = $pdo->query( $upSql );
            if( $res ){
                $resDate["status"] = 1;
                $resDate["msg"] = '成功修改密碼';
            }
            echo json_encode( $resDate );
            return true;
        } catch ( Exception $e ) {
            $resDate["msg"] = $e->getMessage();
            echo json_encode( $resDate );
            return true;
        }
        break;
    default:
        $resDate["msg"] = "不可請求,I wish you know what you do.";
        echo json_encode( $resDate );
        return true;
        break;
}


?>
