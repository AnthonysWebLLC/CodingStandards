<h2>Coding Standards Full Report [Generated <?=$reportDateTime?>]</h2>

<?foreach($checkResults AS $checkName => $checkResult){?>
		<br />
		<h3><?=$checkName?></h3>
		<pre><?=$checkResult?></pre>
<?}?>
