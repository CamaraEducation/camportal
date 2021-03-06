<?php
class DocsControl{
    public static function get($id){
		$sql = "SELECT *, TIMEDIFF(CURRENT_TIMESTAMP, created) AS span FROM document WHERE id = '$id'";
		$sql = mysqli_query(conn(), $sql);

		return mysqli_fetch_assoc($sql);
	}

	public static function fetch($category){
		$sql = "SELECT *, TIMEDIFF(CURRENT_TIMESTAMP, created) AS span FROM document WHERE category = '$category' ORDER BY id DESC";
		$sql = mysqli_query(conn(), $sql);

		return mysqli_fetch_all($sql, MYSQLI_ASSOC);
	}

	public static function popular(){
		$popular = StatsController::popular_docs(); $no = 0;
		foreach($popular as $doc){
			$id = $doc['doc_id'];
			$sql = "select name as title, thumbnail from document where id = '$id'";
			$pop[$no] = mysqli_fetch_assoc(mysqli_query(conn(), $sql));
			$pop[$no]['views'] = $doc['total'];  $no++;
		}
		return $pop;
	}

	public static function obscured(){
		$obscured = StatsController::ghost_docs(); $no = 0;
		foreach($obscured as $doc){
			$id = $doc['doc_id'];
			$sql = "select name as title, thumbnail from document where id = '$id'";
			$obs[$no] = mysqli_fetch_assoc(mysqli_query(conn(), $sql));
			$obs[$no]['views'] = $doc['total'];  $no++;
		}
		return $obs;
	}

	public static function create(){
		$title	  = mysqli_real_escape_string(conn(), $_POST['title']);
		$category = mysqli_real_escape_string(conn(), $_POST['category']);
		$class	  = mysqli_real_escape_string(conn(), $_POST['class']);
		$type	  = mysqli_real_escape_string(conn(), $_POST['type']);
		$description = mysqli_real_escape_string(conn(), $_POST['description']);

		$banner = '/' . FileUploader::upload($file = 'thumbnail', $dir = 'docs/cover/');
		$doc	= '/' . FileUploader::upload($file = 'document', $dir = 'docs/source/' );

		$sql = "INSERT INTO document (name, description, thumbnail, source, class, category, type) VALUES ('$title', '$description', '$banner', '$doc', '$class', '$category', '$type')";
		if(mysqli_query(conn(), $sql)){
			header('Location: /document');
		}else{
		   echo 'something wrong';
		}
	}

	public static function views($id){
		$sql = "SELECT count(id) as views FROM doc_views WHERE doc_id = '$id'";
		$sql = mysqli_query(conn(), $sql);
		$doc = mysqli_fetch_assoc($sql);

		return $doc['views'];
	}

	public static function delete($id){
		$sql = "DELETE FROM document WHERE id = '$id'";
		if(mysqli_query(conn(), $sql)){
			echo 'deleted';
		}else{
		   echo 'something wrong';
		}
	}
}
?>