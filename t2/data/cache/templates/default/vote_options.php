<?php /* 2013-11-11 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?> 
<ol class="poll_item_list"> <?php if(!isset($tid))$tid=0; ?> <?php $bcid = rand(0, 19); ?> <?php if(is_array($option)) { foreach($option as $key => $val) { ?> <li> <div class="poll_item_list_check"> <?php if($allowedvote && !$hasvoted) { ?> <input type="<?php echo $vote['input_type']; ?>" name="option[]" value="<?php echo $val['oid']; ?>" 
<?php if($vote['multiple']) { ?>
onclick="checkSelect(this.checked)"
<?php } ?>
/> <?php } ?> <label class="poll_item"><?php echo $val['option']; ?></label> </div> <?php if($bcid>19) { ?> <?php $bcid=$bcid-19 ?> <?php } ?> <div class="bar_bg bc_<?php echo $bcid; ?>"> <div class="bar_left"></div> <div class="bar_middle" id="bar_<?php echo $tid; ?>_<?php echo $key; ?>" len="<?php echo $val['width']; ?>"></div> <div class="bar_right"></div> </div> <?php $bcid++; ?> <div class="poll_percent"><?php echo $val['vote_num']; ?> (<?php echo $val['percent']; ?>%)</div> </li> <?php } } ?> </ol> <script type="text/javascript">
var __Bar_Name__ = 'bar_<?php echo $tid; ?>_';
function $$$(id)
{
return document.getElementById(id);
}
<?php if(!$hasvoted) { ?>
var maxSelect = <?php echo $vote['maxchoice']; ?>;
var alreadySelect = 0;
function checkSelect(sel)
{
if(sel) {
alreadySelect++;
if(alreadySelect == maxSelect) {
var oObj = document.getElementsByName("option[]");
for(i=0; i < oObj.length; i++) {
if(!oObj[i].checked) {
oObj[i].disabled = true;
}
}
}
} else {
alreadySelect--;
if(alreadySelect < maxSelect) {
var oObj = document.getElementsByName("option[]");
for(i=0; i < oObj.length; i++) {
if(oObj[i].disabled) {
oObj[i].disabled = false;
}
}
}
}
}
<?php } ?>
//效查
var optionNum = 
<?php echo count($option) ?>
;
var maxLength = [0,1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12,13,14,15,16,17,18,19];
var timer;
var length = 0;
for(i = 0; i < optionNum; i++)
{
if ($$$(__Bar_Name__ + i)) {
maxLength[i] = $$$(__Bar_Name__ + i).getAttribute('len');
}
}
timer = setInterval(function(){
setLength();
}, 40);
function setLength(){
for (i = 0; i < optionNum; i++)
{
if ($$$(__Bar_Name__ + i)) {
if (length - 1 >= maxLength[i]) {
$$$(__Bar_Name__ + i).style.width = maxLength[i] + "px";
} else {
$$$(__Bar_Name__ + i).style.width = length + "px";
}
length = length + 1;
if (length > 300) {
clearInterval(timer);
}
}
}
}
</script>