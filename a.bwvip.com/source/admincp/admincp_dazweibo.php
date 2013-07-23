<?php
  echo '<body><iframe src="/t/admin.php?mod=topic" name="iframepage" frameborder="0" scrolling="no" width="100%" height="2000" onload="Javascript:SetWinHeight(this)"></iframe></body>';
?>
<script type="text/javascript">

function SetWinHeight(obj)
{
    var win=obj;

	if (document.getElementById)
	{

		if (win && !window.opera)

		{

			if (win.contentDocument && win.contentDocument.body.offsetHeight)

				win.height = win.contentDocument.body.offsetHeight;

			else if(win.Document && win.Document.body.scrollHeight)

				win.height = win.Document.body.scrollHeight;

		}

	}

}
</script>