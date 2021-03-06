<?php
	session_start();

	require_once("../dbconnect.php");
	if (!isset($_SESSION['userSession'])) {
?>
		<script>
			alert('로그인 후 사용해주세요.');
			location.replace('../login.php');
		</script>
<?php

	}

	//$_GET['bno']이 있을 때만 $bno 선언
	if(isset($_GET['bno'])) {
		$bNo = $_GET['bno'];
	}

	if(isset($bNo)) {
		$sql = 'select b_title, b_content, b_id from board_free where b_no = ' . $bNo;
		$result = $db->query($sql);
		$row = $result->fetch_assoc();

		$fileSql = 'select * from tbl_files where BOARD_NO = '. $bNo;
		$fileResult = $db->query($fileSql);
		

		$fileRow = $fileResult->fetch_assoc();
	}

	$query = $db->query("SELECT user_id, username, email, password FROM tbl_users WHERE email='{$_SESSION['userSession_email']}' ");
	$row2=$query->fetch_array();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Leemy.me - 글쓰기</title>
</head>
<body>
	<?php
		include '../header.php';
	 ?>
	<article class="text-center">
		<h3>자유게시판 글쓰기</h3>
		<div id="boardWrite">
			<form action="./write_update.php" method="post" enctype="multipart/form-data">
				<?php
				if(isset($bNo)) {
					echo '<input type="hidden" name="bno" value="' . $bNo . '">';
				}
				?>
		<div class="container">
        <div class="col-lg-12" id="Comments">
            <div class="form-horizontal" style="padding-top: 25px;">
                <div class="form-group">
                    <!-- <label for="bId" class="col-sm-2 control-label">아이디</label> -->
                    <div class="col-sm-10">
						<?php

						  	if(isset($bNo)){
								//echo $row['b_id'];
							} else { ?>
                        		<input type="hidden" class="form-control" name="bID" id="bID"  value="<?php echo $_SESSION['userSession_email']; ?>"placeholder="Enter Id.." readonly>
						<?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <!-- <label for="emailID" class="col-sm-2 control-label">비밀번호</label> -->
                    <div class="col-sm-10">
                        <input type="hidden" class="form-control" name="bPassword" id="bPassword" value="<?php echo $row2['password'] ?> ">
                    </div>
                </div>

                 <div class="form-group">
                    <label for="emailID" class="col-sm-2 control-label">제목</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="bTitle" id="bTitle" value="<?php echo isset($row['b_title'])?$row['b_title']:null?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="comments" class="col-sm-2 control-label">내용</label>
                    <div class="col-sm-10">
                          <textarea type="text" style="height:500px;" class="form-control" name="bContent" id="bContent"><?php echo isset($row['b_content'])?$row['b_content']:null?></textarea>
                    </div>
                </div>
            </div>
        </div>
		</div>
		
		<div class="container">

		<?php
		if(!isset($bNo))
		{
		?>
			<div class="container">
				<input type="file" name="fileToUpload" id="fileToUpload">
			</div>
		<?php
		}

		else 
		{
			?>
			<div class="container">
				<input type="file" name="fileToUpload" id="fileToUpload">
				<a href = "<?php echo $fileRow['FILE_PATH']; ?>" > <?php echo "첨부된 파일 : " . $fileRow['FILE_NAME'];?> </a>
			</div>
		<?php
		}
		
		?>
		</div>
								
			<div class="btnSet">
				<button type="submit" class="btn btn-primary">
					<?php echo isset($bNo)?'수정':'작성'?>
				</button>
				<a href="./index.php" class="btn btn-danger">목록</a>
			</div>
		</form>
	</div>
	</article>
	<br>
	<?php
		include '../footer.php';
	 ?>
<script>
function DownloadFile($file) 
{ // $file = include path 
    if(file_exists($file)) 
	{
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        ob_clean();
        flush();
        readfile($file);
        exit;
    }

}
</script>
</body>
</html>
