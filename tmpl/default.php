<?php 
defined('_JEXEC') or die;
//$db = JFactory::getDbo();

$document = JFactory::getDocument();
$document->addStyleSheet('/media/com_spc/icons/main.css ');
?>


<div class="th curved">
	<h3 class="t">Re-submit Transaction ID</h3>
</div>
<div class="alert"><?php echo $transaction_id; ?></div>
<div class="art-blockcontent">

	<form action="" method="post">
		If your online payment was successful and you were not automatically credited with SMS units, submit your transaction ID below to get credited immediately.
		<input style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAASCAYAAABSO15qAAAAAXNSR0IArs4c6QAAALZJREFUOBFjYKAANDQ0rGWiQD9IqzgL0BQ3IKMXiB8AcSKQ/waIrYDsKUD8Fir2pKmpSf/fv3+zgPxfzMzMSbW1tbeBbAaQC+b+//9fB4h9gOwikCAQTAPyDYHYBciuBQkANfcB+WZAbPP37992kBgIUOoFBiZGRsYkIL4ExJvZ2NhAXmFgYmLKBPLPAfFuFhaWJpAYEBQC+SeA+BDQC5UQIQpJYFgdodQLLyh0w6j20RCgUggAAEREPpKMfaEsAAAAAElFTkSuQmCC&quot;); background-repeat: no-repeat; background-attachment: scroll; background-size: 16px 18px; background-position: 98% 50%; cursor: auto;" class="full" name="transaction_id" type="text">
		<input class="button art-button" name="submit" value="Submit" type="submit">
	</form>

</div>