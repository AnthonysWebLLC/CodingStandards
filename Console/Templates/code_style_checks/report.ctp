<h2>Coding Standards Full Report [Generated <?=$reportDateTime?>] (<?=sprintf('%01.2f', $secondsRan)?>s)</h2>

<?foreach($checkResults AS $checkName => $checkResult){?>
		<br />
		<h3><?=$checkName?> (<?=sprintf('%01.2f', $checkResult['secondsRan'])?>s)</h3>
		<pre><?=$checkResult['output']?></pre>
<?}?>
