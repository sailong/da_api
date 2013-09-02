/**
 * emptyValue plugin 默认关键字效果 
 * @version 1.3
 * @authod 雨打浮萍
 * Copyright (c) 2012 雨打浮萍 (http://www.rainleaves.com/)
 * For more docs and examples visit:
 * http://www.rainleaves.com/html/1357.html

 //有三种调用方法

//第一种
jQuery("input").emptyValue();
//这中调用方法写起来最简单，但需要所选择input标签含有data-empty属性
//用于记录输入框为空时候的提示内容

//第二种
jQuery("input").emptyValue("请输入要搜索的内容");
//直接将提示内容传递进去，优先级要高于第一种方法

//第三种
jQuery("input").emptyValue({
    empty: "请输入要搜索的内容", //传入提示内容
    className: "gray" //输入框失去焦点时，输入框的样式名，通常用户让字体颜色变灰
});
//gray样式代码：
.gray{
    color:#999;
}

 */
(function($){
	$.fn.val2 = $.fn.val;
	$.fn.emptyValue = function(arg){
        this.each(function(){
            var input = $(this);
            var options = arg;
            if(typeof options == "string"){
                options = {empty: options}
            }
            options = jQuery.extend({
                empty: input.attr("data-empty")||"",
                className: "gray"
            }, options);
            input.attr("data-empty",options.empty);
            return input.focus(function(){
                $(this).removeClass(options.className);
                if($(this).val2() == options.empty){
                    $(this).val2("");
                }
            }).blur(function(){
                if($(this).val2()==""){
                    $(this).val2(options.empty);
                }
                $(this).addClass(options.className);
            }).blur();
        });
    };
    //重写jquery val方法，增加data-empty过滤
    $.fn.val = function(){
    	var value = $(this).val2.apply(this,arguments);
    	var empty = $(this).attr("data-empty");
    	if(typeof empty != "undefined"&&empty==value){
    		value = "";
    	}
    	return value;
    };
})(jQuery);