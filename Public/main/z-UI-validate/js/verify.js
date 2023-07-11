(function(){
    var validate = {};
    var dom = zUI.dom;
    validate.config={
        cht:2,
        msgSec:'<br>',
        errorCallback:function(msg){
            alert(msg);
        }
    };
    validate.extend = function(options){
        if(options){
            for(var k in options){
                validate.config[k] = options[k];
            }
        }
    };
    validate.map={};
    function countLength(a){
        var chinise = a.replace(/[^\u3000-\u301e\ufe10-\ufe19\ufe30-\ufe44\ufe50-\ufe6b\uff01-\uffee\u4E00-\u9FA5—]/g,'');//获取中文部分
        return chinise.length * validate.config.cht + a.length - chinise.length;
    }
    var defaultVerify = {
        //验证非空
        'required':function(a){
            return zUI.util.isEmpty(a)=== false;
        },
        //验证整型
        'integer':function(a){
            return !(defaultVerify['required'])(a) || new RegExp("^\\d+$").test(a);
        },
        //任意数值
        'number':function(a){
            return !(defaultVerify['required'])(a) || new RegExp("^[-+]?\\d+([\\.]\\d+)?$").test(a);
        },
        //邮件
        'email':function(a){
            return !(defaultVerify['required'])(a) || new RegExp("^\\w+([-\\.]\\w+)*@\\w+([-\\.]\\w+)*\\.\\w+([-\\.]\\w+)*$").test(a);
        },
        //手机号
        'phone':function(a){
            return !(defaultVerify['required'])(a) || new RegExp("(^1[34578]\\d{9}$)|(^09\\d{8}$)").test(a);
        },
        //字母
        'letter':function(a){
            return !(defaultVerify['required'])(a) || new RegExp("^[A-Za-z]+$").test(a);
        },
        //字母或下划线开头的英文字符
        'letter_number':function(a){
            return !(defaultVerify['required'])(a) || new RegExp("^[A-Za-z_0-9]+[\\w]?$").test(a);
        },
        //英文字符
        'english':function(a){
            return !(defaultVerify['required'])(a) || new RegExp("^\\w+$").test(a);
        },
    };
    validate.hander = function(formId,obj,dm){
        var devt = validate.devt(formId,obj,dm);
        if(dm.status !== true){
            if(devt.s!== true){
                obj.addEventListener('mousemove',devt.f);
            }
            dom.addClass(obj,"zUI-verify-error");
        }else{
            dom.removeClass(obj,"zUI-verify-error");
            obj.removeEventListener("mousemove",devt.f);
            validate.event.delete(formId,obj);
            dom.addClass(dom.get(formId+"_div"),'zUI-hide');
        }
    }
    validate.event= {};
    validate.event.delete=function(formId,obj){
        var key = formId+"_"+(obj.id||obj.name)+"_"+obj.getAttribute('zUI-verify');
        delete validate.event[key];;
    }
    validate.devt = function (formId,obj,dm) {
        var key = formId+"_"+(obj.id||obj.name)+"_"+obj.getAttribute('zUI-verify');
        var f = validate.event[key];
        if(zUI.util.isF(f)){
            return {f:f,s:true};
        }else{
            validate.event[key] = function(e){
                var ofst = dom.offset(obj);
                var x = e.offsetX + ofst.left + 15;
                var y = e.offsetY + ofst.top - 20 - dom.getScrollTop();
                var div = dom.get(formId+"_div");
                dom.removeClass(div,'zUI-hide');
                div.style.left = x+'px';
                div.style.top = y+'px';
                dom.addClass(div,'zUI-show');
                div.innerText=dm.msg;
            };
        }
        return {f:validate.event[key],s:false};
    };
    validate.init = function(formId){
        var initOne = function(obj){
            var varyFuc = function(){
                var dm = validate.verifyOne(formId,this);
                validate.hander(formId,this,dm);
            }
            obj.addEventListener('blur',varyFuc);
            obj.addEventListener('change',varyFuc);
            obj.addEventListener('input',varyFuc);
            obj.addEventListener('mouseout',function (e) {
                dom.addClass(dom.get(formId+"_div"),'zUI-hide');
            });
        }
        var items = dom.get(formId).querySelectorAll('[zUI-verify]');
        for(var i =0;i <items.length ; i++){
            initOne(items[i]);
        }
        var id = formId;
        if(id.indexOf("#") === 0){
            id = formId.substring(1);
        }
        var errDiv = dom.createElem({name:'div',clazz:'zUI-verify-error-div',id:id+'_div'});
        document.body.appendChild(errDiv);
    };

    validate.verify = function(formId,excludes){
        var $select = document.querySelectorAll(formId+" [zUI-verify]");
        var tips = [];
        var res = true;
        for(var i=0;i<$select.length;i++){
            var obj = $select[i];
            var id = obj.id||obj.name;
            if(id && excludes != undefined){
                var ifIn = false;
                for (let x in excludes) {
                    if(excludes[x] === id){
                        ifIn = true;
                        break;
                    }
                }
                if(ifIn){
                    continue;
                }
            }
            var dm = validate.verifyOne(formId,obj);
            validate.hander(formId,obj,dm);
            if(dm.status !== true){
                res = false;
                if(!zUI.util.arrayContain(tips,dm.msg)){
                    tips.push(dm.msg);
                }
            }
        }
        if(tips.length > 0){
            if(zUI.util.isF(validate.config.errorCallback)){
                validate.config.errorCallback(zUI.util.arrayJoin(tips,validate.config.msgSec));
            }

        }
        return res;
    };
    validate.doVerify = function(formId,verify,value){
        var status = true;
        var func ;
        var def = "";
        if(verify.indexOf('length[') === 0){
            func = function(){
                var maxLenArr = verify.substring(7,verify.length-1).split("-");
                var min = maxLenArr[0];
                var max = maxLenArr[0];
                if(maxLenArr.length == 2){
                    max = maxLenArr[1];
                }
                var len = countLength(value);
                if(min != '' && len < min || max !='' && len > max){

                    return (min == max) ? '长度必须为'+min:'长度必须介于'+min+'-'+max+'之间';
                }
                return true;
            }
        }else if(zUI.util.isF(defaultVerify[verify])){
            func = defaultVerify[verify];
        }else {
            var vrs = validate.map[formId];
            func = vrs[verify];
        }
        if(zUI.util.isF(func)){
            try{
                status = func(value);

                if(status === false){
                    def = false;
                }else if(status !== true){
                    def = status;
                    status = false;
                }

            }catch (e) {
                console.error(e);
            }
        }
        return {def:def,status:status};
    };
    validate.verifyOne = function(formId,obj){
        var status = true;
        var verify = obj.getAttribute('zUI-verify');
        var tip = obj.getAttribute('zUI-tip');

        var value = zUI.util.trim(obj.value);
        if(zUI.util.isEmpty(verify)){
            verify = 'required';
        }
        var arr = verify.split(",");
        var defArr = [];
        for (let v in arr) {
            var res = validate.doVerify(formId,arr[v],value,tip);
            if(!res.status){
                status = res.status;
            }
            if(res.def != "" && res.def !== false){
                defArr.push(res.def);
            }

        }
        var hz = zUI.util.arrayJoin(defArr,validate.config.msgSec);
        var def = zUI.util.isEmpty(tip) ? hz||'验证不通过' : tip ;


        return {status:status,msg:def};
    };
    /**
     * 注册自定义验证方法
     */
    validate.addVerify = function(formId,vrs){
        if(validate.map[formId] === undefined){
            validate.map[formId] = {};
        }
        for(var key in vrs){
            validate.map[formId][key]=vrs[key];
            var items = document.querySelectorAll(formId+' [zUI-verify="'+key+'"');
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                item.onblur = function(){
                    var dm = validate.verifyOne(formId,this);
                    validate.hander(formId,this,dm);
                }
            }
        }
    };
    zUI.onLoad(function(){
        var forms = document.querySelectorAll("form[zUI-form]");
        for (var i in forms) {
            var form = forms[i];
            var id = form.id;
            if(id){
                validate.init("#"+id);
            }
        }
    });
    zUI.validate=validate;
}())


