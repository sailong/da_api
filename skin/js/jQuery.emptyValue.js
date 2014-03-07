/**
 * emptyValue plugin Ĭ�Ϲؼ���Ч�� 
 * @version 1.3
 * @authod ���Ƽ
 * Copyright (c) 2012 ���Ƽ (http://www.rainleaves.com/)
 * For more docs and examples visit:
 * http://www.rainleaves.com/html/1357.html

 //�����ֵ��÷���

//��һ��
jQuery("input").emptyValue();
//���е��÷���д������򵥣�����Ҫ��ѡ��input��ǩ����data-empty����
//���ڼ�¼�����Ϊ��ʱ�����ʾ����

//�ڶ���
jQuery("input").emptyValue("������Ҫ����������");
//ֱ�ӽ���ʾ���ݴ��ݽ�ȥ�����ȼ�Ҫ���ڵ�һ�ַ���

//������
jQuery("input").emptyValue({
    empty: "������Ҫ����������", //������ʾ����
    className: "gray" //�����ʧȥ����ʱ����������ʽ����ͨ���û���������ɫ���
});
//gray��ʽ���룺
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
    //��дjquery val����������data-empty����
    $.fn.val = function(){
    	var value = $(this).val2.apply(this,arguments);
    	var empty = $(this).attr("data-empty");
    	if(typeof empty != "undefined"&&empty==value){
    		value = "";
    	}
    	return value;
    };
})(jQuery);