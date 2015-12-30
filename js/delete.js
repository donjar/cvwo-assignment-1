function delete_post() {
	if (confirm("Are you sure?")) {
		$.post("index.php");
	}
}

$("p").click(function() {
	alert("test");
})