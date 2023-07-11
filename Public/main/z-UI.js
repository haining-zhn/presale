;(function(){
    var zUI = new Object();
    zUI.util = {
        isEmpty: function(a){
            return a=== null || a==='' || a === undefined;
        },
        isF: function(a){
            return typeof a === 'function';
        },
        trim: function(v){
            return this.isEmpty(v) ? '' : v.replace(/^\s\s*/, '' ).replace(/\s\s*$/, '' );
        },
        htmlEncode: function(s){
            return (typeof s != "string") ? s :
                s.replace(/"|&|'|<|>|[\x00-\x20]|[\x7F-\xFF]|[\u0100-\u2700]/g,
                    function($0){
                        var c = $0.charCodeAt(0), r = ["&#"];
                        c = (c == 0x20) ? 0xA0 : c;
                        r.push(c); r.push(";");
                        return r.join("");
                    });
        },
        arrayJoin: function(a,d){
            if(a instanceof Array) {
                var c="";
                for(var b=0;b<a.length;b++){
                    if(b>0){
                        c+=d;
                    }
                    c+=a[b];
                }
                return c;
            }
            return"";
        },
        arrayContain: function(a,c){
            if(a instanceof Array){
                for(var b=0;b<a.length;b++){
                    if(c===a[b]){
                        return true;
                    }
                }
            }
            return false;
        },
        UUID: function () {
            var str = '0123456789abcdefghijklmnopqrstuvwxyz';
            var arr = [];
            for(var i = 0; i < 32; i++){
                arr.push(str.substr(Math.floor(Math.random() * 37), 1))
            }
            return this.arrayJoin(arr,'');
        },
        each: function (obj,func) {
            if(obj){
                for (var i = 0; i < obj.length; i++) {
                    func(i,obj[i]);
                }
            }
        }
    };
    zUI.form = {
        reset: function(formObj,excludes){
            var form = typeof formObj === 'string' ? dom.get(formObj) : formObj;
            var items = form.querySelectorAll('input,select,textarea');
            zUI.util.each(items,function (i,o) {
                var pass = false;
                if(excludes && o.id){
                    for(var i=0;i<excludes.length;i++){
                        if(id === excludes[i]){
                            pass = true;
                            break;
                        }
                    }
                }
                if(pass){
                    return;
                }
                var type = o.getAttribute('type')||'text';
                if(type === 'radio' || type === 'checkbox'){
                    o.checked = false;
                }else{
                    o.value = '';
                }
            });
            this.render();

        },
        getData: function(formObj){
            var res = {};
            var form = typeof formObj === 'string' ? dom.get(formObj) : formObj;
            var items = form.querySelectorAll('input,select,textarea');
            for (var i = 0; i < items.length; i++) {

                var o = items[i];
                if(o.name){
                    var v = null;
                    if(dom.is(o,'input')){
                        var type = o.getAttribute('type')||'text';

                        if(type === 'radio' || type === 'checkbox'){

                            v = o.checked ? o.value : v;
                        }else{
                            v = o.value||'';
                        }
                    }else{
                        v = o.value||'';
                    }
                    if(v !== null){
                        var oldV = res[o.name];
                        var newV;
                        if(oldV === undefined){
                            newV = v;
                        }else if(typeof oldV === 'string'){
                            newV = [oldV,v];
                        }else if(typeof oldV === 'object'){
                            oldV.push(v);
                            newV = oldV;
                        }
                        res[o.name] = newV;
                    }

                }
            }
            for(var n in res){
                if(typeof res[n]=== 'object'){
                    res[n] = res[n].toString();
                }
            }
            return res;
        },
        render: function (){
            var forms = dom.gets('.zUI-form');
            zUI.util.each(forms,function (i,o) {
                var radios = o.querySelectorAll('input.zUI-input[type=radio]');
                zUI.util.each(radios,function (j,r) {
                    dom.addClass(r,'zUI-hide');
                    var title = r.getAttribute('title')||'';
                    var disabled = (r.getAttribute('disabled')||'')=== 'disabled';
                    var name = r.getAttribute('name');
                    var gp = dom.gets('input[type=radio][name=\''+name+'\']');
                    var wrap = r.nextElementSibling;
                    var clazz = 'zUI-rc-wrap zUI-radio-wrap'+(r.checked === true ? ' zUI-checked':'')+(disabled ?' disabled':'');
                    if(wrap == null || !dom.hasClass(wrap,'zUI-rc-wrap')){
                        wrap = dom.createElem({name:'label'});
                        var text = document.createTextNode(title);
                        var radio = dom.createElem({name:'div',clazz:'zUI-radio'});
                        wrap.appendChild(radio);
                        wrap.appendChild(text);
                        dom.insertAfter(wrap,r);
                    }
                    wrap.className = clazz;
                    var f = function(wp,ro){
                        return function () {
                            var err = 0;
                            zUI.util.each(gp,function(ii,oo){
                                if(oo.disabled && oo.checked){
                                    err++;
                                }
                            });
                            if(err> 0){
                                return;
                            }
                            var ck = dom.hasClass(wp,'zUI-checked');
                            if(!ck){
                                zUI.util.each(gp,function(ii,oo){
                                    if (oo === ro){
                                        oo.checked = true;
                                        dom.addClass(wp,'zUI-checked');
                                    }else{
                                        oo.checked = false;
                                        dom.removeClass(oo.nextElementSibling,'zUI-checked');
                                    }
                                })
                            }
                        }

                    }(wrap,r);
                    if(!disabled){
                        wrap.onclick = f;
                    }

                });
                var checkBoxs = o.querySelectorAll('input.zUI-input[type=checkbox]');
                zUI.util.each(checkBoxs,function (j,r) {
                    dom.addClass(r,'zUI-hide');
                    var title = r.getAttribute('title')||'';
                    var disabled = (r.getAttribute('disabled')||'')=== 'disabled';
                    var name = r.getAttribute('name');
                    var wrap = r.nextElementSibling;
                    var clazz = 'zUI-rc-wrap zUI-checkbox-wrap'+(r.checked === true ? ' zUI-checked':'')+(disabled ? ' disabled':'');
                    if(wrap == null || !dom.hasClass(wrap,'zUI-rc-wrap')){
                        wrap = dom.createElem({name:'label'});
                        var text = document.createTextNode(title);
                        var radio = dom.createElem({name:'div',clazz:'zUI-checkbox'});
                        wrap.appendChild(radio);
                        wrap.appendChild(text);
                        dom.insertAfter(wrap,r);
                    }
                    wrap.className = clazz;
                    var f = function(wp,ro){
                        return function () {
                            var ck = dom.hasClass(wp,'zUI-checked');
                            if(ck){
                                dom.removeClass(wp,'zUI-checked');
                                ro.checked = false;
                            }else{
                                dom.addClass(wp,'zUI-checked');
                                ro.checked = true;
                            }
                        }

                    }(wrap,r);
                    if(!disabled){
                        wrap.onclick = f;
                    }

                });
            });
        }
    }
    zUI.onLoad = function(func){
        var oldonload = window.onload;
        if(typeof window.onload != 'function'){
            window.onload = func;
        }else{
            window.onload = function(){
                oldonload();
                func();
            }
        }

    }

    zUI.event={
        map: {},
        bind: function(key,obj,type,fn,opt){
            obj.addEventListener(type,fn,opt=!!opt);
            this.map[key] = {obj:obj,type:type,fn:fn,opt:opt};
        },
        unbind: function(key){
            var d = this.map[key];
            if(d){
                if(window.removeEventListener){
                    d.obj.removeEventListener(d.type, d.fn,d.opt);
                }else if(window.detachEvent){
                    d.obj.detachEvent('on' + d.type, d.fn,d.opt);
                }
            }
        }
    };

    var dom = {
        isVisable: function(elem){
            return elem.offsetParent !== null && !this.hasClass(elem,'zUI-hide');
        },
        getScrollTop: function (){
            var scroll_top = 0;
            if (document.documentElement && document.documentElement.scrollTop) {//如果非IE
                scroll_top = document.documentElement.scrollTop;
            }
            else if (document.body) {//IE浏览器
                scroll_top = document.body.scrollTop;
            };
            return scroll_top;
        },
        removeEvent: function(elem,type,fn){
            if(window.removeEventListener){
                elem.removeEventListener(type, fn);
            }else if(window.detachEvent){
                elem.detachEvent('on' + type, fn);
            }
        },
        createElem: function(opt){
            var element = opt.name,clazz=opt.clazz,id=opt.id,text=opt.text,html=opt.html;
            var d = document.createElement(element);
            if(clazz){
                d.setAttribute('class',clazz);
            }
            if(id){
                d.setAttribute('id',id);
            }
            if(text){
                d.appendChild(document.createTextNode(text));
            }
            if(html){
                d.innerHTML = html;
            }
            return d;
        },
        css: function(obj,attr){
            return obj.currentStyle ? obj.currentStyle[attr] : getComputedStyle(obj, false)[attr];
        },
        addClass: function(node,clazz){
            if(node instanceof NodeList){
                for(var i=0;i<node.length;i++){
                    dom.addClassOne(node[i],clazz);
                }
            }else{
                dom.addClassOne(node,clazz);
            }
        },
        addClassOne: function(node,clazz){
            var classs = node.className.trim();
            var cs = classs ? classs.split(' ') : [];
            var as = clazz.split(' ');
            for (var x in as) {
                if(cs.indexOf(as[x])=== -1){
                    classs += ' '+as[x];
                }
            }
            node.className=classs;
        },
        hasClass: function(node,clazz){
            var classs = node.className;
            var cs = classs ? classs.split(' ') : [];
            return cs.indexOf(clazz)!== -1;
        },
        removeClass: function(node,clazz){
            if(node instanceof NodeList){
                for(var i=0;i<node.length;i++){
                    dom.removeClassOne(node[i],clazz);
                }
            }else{
                dom.removeClassOne(node,clazz);
            }
        },
        removeClassOne: function(node,clazz){
            var classs = node.className;
            var cs = classs ? classs.split(' ') : [];
            var i;
            var rc = clazz.split(' ');
            for (var x in rc) {
                if((i=cs.indexOf(rc[x]))!== -1){
                    delete cs[i];
                }
            }
            node.className=cs.join(' ').trim();
        },
        remove: function(elem){
            if(elem){
                if(typeof elem === 'string'){
                    var r = document.querySelectorAll(elem);
                    var that = this;
                    zUI.util.each(r,function(i,o){
                        that.removeNode(o);
                    })
                }else{
                    this.removeNode(elem);
                }
            }
        },
        removeNode: function(node){
            if(node != null){
                if(node.parentNode != null){
                    node.parentNode.removeChild(node)
                }
            }
        },
        insertAfter: function(newElement,targetElement){
            var parent = targetElement.parentNode;
            if(parent.lastChild == targetElement){
                parent.appendChild(newElement);
            }
            else{
                parent.insertBefore(newElement,targetElement.nextSibling);
            }
        },
        swapNode: function (aNode,bNode){
            var aParent = aNode.parentNode;
            var bParent = bNode.parentNode;
            if(aNode && bNode){
                var aNode2 = aNode.cloneNode(true);//aNode 没有父节点
                bParent.replaceChild(aNode2,bNode);
                aParent.replaceChild(bNode,aNode);
                return aNode2;
            }
        },
        empty: function(node){
            node.innerHTML='';
        },
        is: function(node,tagName){
            var n= node.tagName.toUpperCase();
            return tagName.toUpperCase() === n;
        },
        offset: function (curEle){
            var totalLeft = null,totalTop = null,par = curEle.offsetParent;
            //首先把自己本身的进行累加
            totalLeft += curEle.offsetLeft;
            totalTop += curEle.offsetTop;

            //只要没有找到body，我们就把父级参照物的边框和偏移量累加
            while(par){
                if(navigator.userAgent.indexOf("MSIE 8.0") === -1){
                    //不是标准的ie8浏览器，才进行边框累加
                    //累加父级参照物边框
                    totalLeft += par.clientLeft;
                    totalTop += par.clientTop;
                }
                //累加父级参照物本身的偏移
                totalLeft += par.offsetLeft;
                totalTop += par.offsetTop;
                par = par.offsetParent;
            }
            return {left:totalLeft,top:totalTop};
        },
        get: function(elem){
            if(elem == null){
                return null;
            }
            return typeof elem === 'string' ? document.querySelector(elem) : elem;
        },
        gets: function(elem){
            if(elem == null){
                return null;
            }
            return typeof elem === 'string' ? document.querySelectorAll(elem) : elem;
        },
        getW: function(node){
            return node.style.width || node.offsetWidth;
        },
        getH: function(node){
            return node.style.height || node.offsetHeight;
        },
        makeSVG: function(opt) {
            var tag=opt.name,id=opt.id,clazz=opt.clazz;
            var ns = 'http://www.w3.org/2000/svg';
            var el= document.createElementNS(ns, tag);
            el.setAttribute("class",clazz);
            el.setAttribute("id",id);
            return el;
        }
    };
    zUI.onLoad(zUI.form.render);
    zUI.dom=dom;

    window.zUI = zUI;
}());