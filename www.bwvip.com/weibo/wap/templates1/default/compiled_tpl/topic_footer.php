<?php /* 2013-03-13 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><br />
<?php echo $GLOBALS['schedule_html']; ?>
</body> 
</html>
<?php if($this->MemberHandler) $this->MemberHandler->UpdateSessions(); ?>