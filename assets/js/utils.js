/*创建我的全局通用组件*/
'use strict'
//控制接单按钮状态--防止双击
function cancelClick(id,secs) {
    var secs = secs || 5;
    var text = $("#"+id).text();
    btnState(id, secs, text);
}
//控制接单按钮状态--防止双击
function btnState(btnId,secs,text)
{
    var obj=document.getElementById(btnId);
    if(secs-- > 0)
    {
        $(obj).text("("+secs+")秒")
        obj.setAttribute('disabled','disabled');
        setTimeout("btnState('" + btnId + "', " + secs + ", '" + text + "');", 1000);
    } else {
        $(obj).text(text);
        obj.removeAttribute("disabled");
    }
}

var Utils = function () {

    var baseUrl = function (url) {
        var baseUrl = $("#input-baseUrl").val();
        return baseUrl + '/' + url;
    };

    var siteUrl = function (url) {
        var siteUrl = $("#input-siteUrl").val();
        return siteUrl + '/' + url;
    };

    var noticeSuccess = function (msg) {
        notice(msg, 'success');
    };

    var noticeWarning = function (msg) {
        notice(msg, 'warning');
    };

    var noticeError = function (msg) {
        notice(msg, 'error');
    };

    var noticeSys = function (data) {
        if (data.success) {
            noticeSuccess(data.msg);
        } else {
            noticeWarning(data.msg);
        }
    }


    var notice = function (msg, style, time) {
        spop({
            template: msg,
            group: 'lnfilm',
            position: 'top-center',
            style: style || 'info',
            autoclose: time || 2000
        });
    };

    // 提示组件 制作 Confirm 采用 Javascript 回调函数
    var handleConfirm = function (title, subTitle, callback) {
        swal({
            title: title,
            text: subTitle,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "是",
            cancelButtonText: "否",
            closeOnConfirm: true,
            closeOnCancel: true,
        }, function (isConfirm) {
            if (isConfirm && typeof callback == 'function') {
                callback();
            }
        });
    };

    var sleep = function (second, callback) {
        if (typeof callback == 'function') {
            setTimeout(callback, second * 1000);
        }
    };


    var confirm = function (opt) {
        swal({
            title: opt.title || "标题",
            text: opt.text || "内容",
            icon: opt.icon || 'warning',
            buttons: {
                cancel: "取消",
                confirm: {
                    text: "确认"
                },
            },
            dangerMode: true,
        }).then(function (confirm) {
            if (confirm) {
                if (opt.confirm) {
                    opt.confirm();
                }
            }
            else {
                if (opt.cancel) {
                    opt.cancel();
                }
            }
        });
    };

    var alert = function (opt) {
        opt.text = opt.text || "内容";
        opt.icon = opt.icon || 'success';
        opt.buttons = '确定';
        swal(opt);
    };

    var alertSuccess = function (text) {
        var opt = {
            text: text || '内容缺失',
            icon: 'success',
            buttons: '确定'
        };
        swal(opt);
    };

    var alertError = function (text) {
        var opt = {
            text: text || '内容缺失',
            icon: 'error',
            buttons: '确定'
        };
        swal(opt);
    };
    
    var alertSys = function (data) {
        if(data.success) {
            alertSuccess(data.msg);
        } else {
            alertError(data.msg);
        }
    }

    // 表单验证
    var loginValidate = function (form, rules, callback) {
        var error = $('.alert-danger');
        var success = $('.alert-success');
        error.hide();
        success.hide();
        form.validate({
            rules: rules,
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            invalidHandler: function (event, validator) { //display error alert on form submit
                success.hide();
            },
            errorPlacement: function (error, element) { // 添加错误内容
                $(element).closest('.form-group').children('label').remove();
                if (error.text() != "") {
                    $(element).closest('.form-group').append('<label class="control-label"><i class="fa fa-times-circle-o"></i> ' + error.text() + '</label>');
                }
            },
            highlight: function (element) { // 高亮
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            success: function (label, element) {
                //某一个元素成功后的事件
                $(element).closest('.form-group').addClass('has-success');
                $(element).closest('.form-group').children('label').remove();
                $(element).closest('.form-group').append('<label class="control-label"><i class="fa fa-check"></i> 录入正确</label>');

            },
            submitHandler: function (form) {
                success.show();
                callback();
            }
        });
    };


    // 模拟表单验证提交
    var formValidate = function (form, rules, callback) {
        form.validate({
            rules: rules,
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            errorPlacement: function (error, element) { // 添加错误内容
                $(element).closest('.form-group').children('label.control-label').remove();
                if (error.text() != "") {
                    $(element).closest('.form-group').append('<label class="control-label"><i class="fa fa-times-circle-o"></i> ' + error.text() + '</label>');
                }
                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
                $(element).closest('.form-group').removeClass('has-success');
            },
            success: function (label, element) {
                //某一个元素成功后的事件
                $(element).closest('.form-group').removeClass('has-error');
                $(element).closest('.form-group').addClass('has-success');
                $(element).closest('.form-group').children('label.control-label').remove();
                $(element).closest('.form-group').append('<label class="control-label"><i class="fa fa-check"></i> 录入正确</label>');
            },
            submitHandler: function (form) {
                callback();
            }
        });
    };

    var ajax = function (opt) {
        if (opt.url === undefined) {
            noticeError('请求地址不能为空');
            return;
        }
        $.ajax({
            url: opt.url,
            data: opt.data || {},
            type: opt.type || 'post',
            dataType: opt.dataType || 'json',
            async: opt.async || true,
            success: function (data) {
                if (data.relogin == true) {
                    // noticeWarning(data.msg);
                    sleep(1, function () {
                        var loginUrl = siteUrl('loginout/index') + '?backUrl=' + window.location.href;
                        window.location.href = loginUrl;
                    });
                    return;
                }
                if( data.noPower == true) {
                    alertError(data.msg);
                    return;
                }
                if (opt.success) {
                    opt.success(data);
                }
            },
            error: function (data) {
                noticeError('系统异常');
                console.log(data.responseText);
                if (opt.error) {
                    opt.error();
                }
            }
        });
    };

    var formAjax = function (form, opt) {
        if (opt.url === undefined) {
            noticeError('请求地址不能为空');
            return;
        }
        form.ajaxSubmit({
            url: opt.url,
            data: opt.data || {},
            type: 'post',
            dataType: 'json',
            success: function (data) {
                if (data.relogin == true) {
                    noticeWarning(data.msg);
                    sleep(1, function () {
                        var loginUrl = siteUrl('loginout/index') + '?backUrl=' + window.location.href;
                        window.location.href = loginUrl;
                    })
                    return;
                }
                if (opt.success) {
                    opt.success(data);
                }
            },
            error: function (data) {
                noticeError('系统异常');
                console.log(data.responseText);
                if (opt.error) {
                    opt.error();
                }
            }
        });
    }

    var getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null)return unescape(r[2]);
        return null;
    };

    var dateInit = function () {

        // 只选择 天
        $(".form-date.date-day").datetimepicker({
            language:  "zh-CN",
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            maxView: 3,
            forceParse: 0,
            format: 'yyyy-mm-dd',
        });

        // 只选择 月
        $(".form-date.date-month").datetimepicker({
            language:  "zh-CN",
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 3,
            minView: 3,
            maxView: 3,
            forceParse: 0,
            format: 'yyyy-mm'
        });
    }

    var clearDatetimePicker = function (obj){
        $(obj).closest('.input-group').find("input").val("");
    }

    var scrollTo = function (el, offeset) {
        var pos = (el && el.size() > 0) ? el.offset().top : 0;

        if (el) {
            if ($('body').hasClass('page-header-fixed')) {
                pos = pos - $('.page-header').height();
            } else if ($('body').hasClass('page-header-top-fixed')) {
                pos = pos - $('.page-header-top').height();
            } else if ($('body').hasClass('page-header-menu-fixed')) {
                pos = pos - $('.page-header-menu').height();
            }
            pos = pos + (offeset ? offeset : -1 * el.height());
        }

        $('html,body').animate({
            scrollTop: pos
        }, 'slow');
    };

    // 图片连接错误
    var getSelectChildHtml = function (parentId, childId, dataList, valKey, textKey, limit, childVal) {
        var parentVal = $("#"+parentId).val();
        console.log(parentVal);
        var data = dataList[parentVal];
        console.log(data);
        if(limit == undefined) {
            limit = true;
        } else {
            if(limit !== false) {
                limit === true;
            }
        }
        var html = limit ? "<option value='-1'>不限</option>" : '';
        if (data) {
            for (var i = 0; i < data.length; i++) {
                html = html + "<option value='" + data[i][valKey] + "'>" + data[i][textKey] + "</option>"
            }
        }
        console.log(html);
        $("#"+childId).html(html);
        if (childVal !== undefined && childVal != 0) {
            $("#"+childId).val(childVal);
        }
    };


    return {
        init: function () {
        },
        baseUrl: function (url) {
            return baseUrl(url);
        },
        siteUrl: function (url) {
            return siteUrl(url);
        },
        sleep: function (second, callback) {
            sleep(second, callback);
        },
        notice: function (msg, style, time) {
            notice(msg, style, time);
        },
        noticeSuccess: function (msg) {
            noticeSuccess(msg);
        },
        noticeWarning: function (msg) {
            noticeWarning(msg);
        },
        noticeError: function (msg) {
            noticeError(msg);
        },
        noticeSys: function (data) {
            noticeSys(data);
        },
        loginValidate: function (form, rules, callback) {
            loginValidate(form, rules, callback);
        },
        formValidate: function (form, rules, callback) {
            formValidate(form, rules, callback);
        },
        ajax: function (opt) {
            ajax(opt);
        },
        formAjax: function (form, opt) {
            formAjax(form, opt);
        },
        confirm: function (opt) {
            confirm(opt);
        },
        alertSuccess: function (text) {
            alertSuccess(text);
        },
        alertError: function (text) {
            alertError(text);
        },
        alertSys: function (text) {
            alertSys(text);
        },
        getUrlParam: function (name) {
            return getUrlParam(name);
        },
        scrollTo: function (el, offeset) {
            scrollTo(el, offeset);
        },
        getSelectChildHtml: function (parentId, childId, dataList, valKey, textKey, limit, childVal) {
            getSelectChildHtml(parentId, childId, dataList, valKey, textKey, limit, childVal);
        },
        dateInit: function () {
            dateInit();
        },
        clearDatetimePicker: function (obj) {
            clearDatetimePicker(obj);
        }
    };
}();

jQuery(document).ready(function () {
    Utils.init();
});
