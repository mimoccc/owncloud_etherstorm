<?php 

error_reporting(0);
//error_reporting(E_ALL);

OCP\User::checkLoggedIn();
if ((isset($_POST['etherpadauthuser'])) && (isset($_POST['etherpadauthpw']))){
if (isset($_POST['etherpadauthuser'])) {preg_match_all('/^[a-zA-Z0-9_]{0,99}$/',$_POST['etherpadauthuser'],$etherpadauthuser);$etherpadauthuser=$etherpadauthuser[0][0];}
if (isset($_POST['etherpadauthpw'])) {preg_match_all('/^[a-zA-Z0-9_]{0,99}$/',$_POST['etherpadauthpw'],$etherpadauthpw);$etherpadauthpw=$etherpadauthpw[0][0];}

$query=OC_DB::prepare("delete from *PREFIX*etherstorm_config where user = ?");
$result=$query->execute(array(OCP\User::getUser()));
$query=OC_DB::prepare("insert into *PREFIX*etherstorm_config (user,authuser,authpw) values (?,?,?)");
$result=$query->execute(array(OCP\User::getUser(),$etherpadauthuser,$etherpadauthpw));
if ($result) {die("true");}
}

if (isset($_POST['etherpadurl']) && isset($_POST['apikey'])){
OCP\User::checkAdminUser();
//if (isset($_POST['etherpadurl'])) {preg_match_all('/(http|ftp|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?/g',$_POST['etherpadurl'],$etherpadurl);$etherpadurl=$etherpadurl[0][0];}
if (isset($_POST['apikey'])) {preg_match_all('/^[a-zA-Z0-9_]{1,50}$/',$_POST['apikey'],$apikey);$apikey=$apikey[0][0];}
$query=OC_DB::prepare("delete from *PREFIX*etherstorm_config where user is null");
$result=$query->execute();
$query2=OC_DB::prepare("insert into *PREFIX*etherstorm_config (url,apikey) values (?,?)");
$result2=$query2->execute(array($_POST['etherpadurl'],$apikey));
if ($result2) {die("true");}

}

?>

<?php

if (isset($_POST['padname'])) {preg_match_all('/^[a-zA-Z0-9_]{5,99}$/',$_POST['padname'],$padname);$padname=$padname[0][0];}
if (isset($_POST['priority'])) {preg_match_all('/^[0-9]{1,9}$/',$_POST['priority'],$priority);$priority=$priority[0][0];}
if (isset($_POST['comment'])) {preg_match_all('/^[a-zA-Z0-9_]{4,99}$/',$_POST['comment'],$comment);$comment=$comment[0][0];}
if (!isset($padserver)) {
$query=OC_DB::prepare("select url from *PREFIX*etherstorm_config where user is null");
$result=$query->execute();
$data = $result->fetchAll();
$padserver = $data[0]["url"];
}


function getAuthPW($pw) {
$query=OC_DB::prepare("select authuser from *PREFIX*etherstorm_config where user = ?");
$result=$query->execute(array($pw));
$data = $result->fetchAll();
$authuser = $data[0]["authuser"];
return $authuser;
}
function getAuthUser($user) {
$query=OC_DB::prepare("select authpw from *PREFIX*etherstorm_config where user = ?");
$result=$query->execute(array($user));
$data = $result->fetchAll();
$authpw = $data[0]["authpw"];
return $authpw;
}

if (getAuthPW(OCP\User::getUser()) <> "" and getAuthUser(OCP\User::getUser()) <> "") {
$Url = preg_match("/^(https?:\/\/)?(.+)$/",$padserver,$UrlArray);
$padserver = $UrlArray[1].getAuthUser(OCP\User::getUser()).":".getAuthPW(OCP\User::getUser())."@".$UrlArray[2];
}


if (!isset($apikey)) {
$query=OC_DB::prepare("select apikey from *PREFIX*etherstorm_config where user is null");
$result=$query->execute();
$data = $result->fetchAll();
$apikey = $data[0]["apikey"];
}

if (isset($_POST['getallpads'])) {echo file_get_contents($padserver.'listAllPads?apikey='.$apikey);}

elseif (isset($_POST['createpad'])) {
$text = json_decode(file_get_contents($padserver.'createPad?apikey='.$apikey.'&padID='.$padname)); 
if ($text->code == 0) { 
$query=OC_DB::prepare("insert into *PREFIX*etherstorm (padname,creator,created,priority,comment_author,comment_text,comment_time) values (?,?,?,?,?,?,?)");
$result=$query->execute(array($padname,OCP\User::getUser(),time(),$priority,OCP\User::getUser(),$comment,time()));
echo json_encode("true");
} 
else {echo $text->message;}

}
elseif (isset($_POST['getpadserver'])) {
echo json_encode($padserver);
}
 
elseif (isset($_POST['deletepad'])) {
$text = json_decode(file_get_contents($padserver.'getText?apikey='.$apikey.'&padID='.$padname)); 
if ($text->code == 0) {
mail('dummy@localhost', 'Sicherung Etherpad'.$padname, $text->data->text, "From: Etherstorm");
$text = json_decode(file_get_contents($padserver.'deletePad?apikey='.$apikey.'&padID='.$padname)); 
if ($text->code == 0) { 
$query=OC_DB::prepare("delete from *PREFIX*etherstorm where padname=?");
$result=$query->execute(array($padname));echo json_encode("true");} 
else {echo $text->message;}
 

}else {echo $text->message;}} 


elseif (isset($_POST['comment'])) {
$query=OC_DB::prepare("insert into *PREFIX*etherstorm (padname,comment_author,comment_text,comment_time) values (?,?,?,?)");
$result=$query->execute(array($padname,OCP\User::getUser(),$comment,time()));
echo json_encode("true");
}

elseif (isset($_POST['getcomments'])) {
$query=OC_DB::prepare("select comment_author,comment_text,comment_time from *PREFIX*etherstorm where padname =? and creator is null");
$result=$query->execute(array($padname));
$data = $result->fetchAll();
$content = "<div style='overflow-x: auto;overflow-y: auto;width: 100%;'><table style='word-wrap:break-word'>";
foreach($data as $item) {
    $content .= "<tr style='padding:5px;'>"."<td style='padding:5px'>".$item['comment_author']."</td>"."<td style='padding:5px'>".$item['comment_text']."</td>"."<td style='padding:5px'>".date("H:i:s d.m.Y",$item['comment_time'])."</td>"."</tr>";
}
$content .= "</table></div>";
echo json_encode($content);
}

elseif (isset($_POST['priority'])) {
$query=OC_DB::prepare("update *PREFIX*etherstorm set priority = ? where padname=? and creator is not null");
$result=$query->execute(array($_POST['priority'],$padname));
echo json_encode("true");
}

else {
$query=OC_DB::prepare("select creator,created,priority from *PREFIX*etherstorm where padname=? and creator is not null");
$result=$query->execute(array($padname));
$lastedited = json_decode(file_get_contents($padserver.'getLastEdited?apikey='.$apikey.'&padID='.$padname));
$query=OC_DB::prepare("select count(comment_text) as kommentaranzahl from *PREFIX*etherstorm where padname=? and creator is null");
$result2=$query->execute(array($padname));

$data=$result->fetchAll();
$data2=$result2->fetchAll();
if (isset($data)) {$data[0]["lastedited"] = date("H:i:s d.m.Y",($lastedited->data->lastEdited/1000));$data[0]["created"] = date("H:i:s d.m.Y",$data[0]["created"]);$data[0]["kommentaranzahl"] = $data2[0]["kommentaranzahl"];print_r(json_encode($data));}
}
?>