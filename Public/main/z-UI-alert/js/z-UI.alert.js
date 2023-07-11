;(function(){
    zUI.dialog={
        maskCount:0,
        alertUI:function(set){
            var dom = zUI.dom;
            var title = set.title||'提示';
            var width = set.width;
            var height = set.height;
            var close = set.close;
            var draggable = set.draggable !== false;
            var resizable = set.resizable !== false;
            var shadow = set.shadow !== false;
            var btns = set.btns||null;
            var id = set.id||('zUI_'+zUI.util.UUID());
            if(dom.get('#'+id)!== null){
                return false;
            }
            var shadowDiv = null;
            if(shadow){
                shadowDiv = dom.createElem({name:'div',clazz:'zUI-shadow',id:'mark_'+id});
                document.body.appendChild(shadowDiv);
            }
            var content = set.content||'';
            var obj = dom.createElem({name:'div',clazz:'zUI-box',id:id});

            var tit = dom.createElem({name:'div',clazz:'zUI-title'+ (draggable?' draggable':'')});

            obj.appendChild(tit);
            var h1 = dom.createElem({name:'h1',clazz:'zUI-title',text:title});

            tit.appendChild(h1);

            var dv2 = document.createElement('div');
            var clo = dom.createElem({name:'a',text:'×'});
            dv2.appendChild(clo);
            tit.appendChild(h1);
            tit.appendChild(dv2);

            var cnt = dom.createElem({name:'div',clazz:'zUI-content',html:content});

            obj.appendChild(cnt);
            var btnBox = dom.createElem({name:'div',clazz:'zUI-btns'});
            if(btns !== null){
                obj.appendChild(btnBox);
            }else{
                dom.addClass(cnt,'not-btn');
            }

            document.body.appendChild(obj);
            if(height){
                obj.style.height=height;
            }
            if(width){
                obj.style.width=width;
            }
            function isF(f){
                return typeof f === 'function';
            }
            var btnArr = [];
            if(btns !== null){
                for(var bn in btns){
                    var f = btns[bn];
                    //防止循环结束后，回调时bn永远是最后一个值
                    var b = dom.createElem({name:'a',clazz:'zUI-btn',text:bn});
                    b.setAttribute('href','javascript:;');
                    btnBox.appendChild(b);
                    if(isF(f)){
                        b.onclick = function(ff){
                            return function () {
                                if(ff() !== false){
                                    dom.remove(shadowDiv);
                                    dom.remove(obj);
                                    if(zUI.util.isF(close)){
                                        close();
                                    }
                                }
                            }
                        }(f);
                    }
                    btnArr.push(b);
                }
            }

            if(draggable){
                tit.onmousedown=function(e){
                    var e = e || window.event;
                    var state = 1;
                    var x0 = e.clientX - obj.offsetLeft;
                    var y0 = e.clientY - obj.offsetTop;
                    /*if(typeof tit[0].setCapture !== 'undefined'){
                        tit[0].setCapture();
                    }*/
                    var f1 = function(e1){
                        e1 = e1 || window.event;
                        if(state == 1){
                            var moveX = e1.clientX - x0;
                            var moveY = e1.clientY - y0;
                            if(moveX < 0){
                                moveX = 0
                            }else if(moveX > window.innerWidth - tit.offsetWidth){
                                moveX = window.innerWidth - tit.offsetWidth
                            }
                            if(moveY < 0){
                                moveY = 0
                            }else if(moveY > window.innerHeight - tit.offsetHeight){
                                moveY =  window.innerHeight - tit.offsetHeight
                            }
                            obj.style.left = moveX + 'px';
                            obj.style.top = moveY + 'px'
                        }
                    }
                    var f2 = function(e2){
                        state = 0;
                        dom.removeEvent(document,"mousemove",f1);
                        dom.removeEvent(document,"mouseup",f2);
                    }
                    document.onmousemove = f1;
                    document.onmouseup = f2;

                };
            }
            if(resizable){
                var tool = dom.createElem({name:'span',clazz:'zUI-resize'});
                obj.appendChild(tool);
                tool.onmousedown=function(e){
                    var e = e || window.event;
                    var state = 1;
                    var ww = parseInt(obj.style.width||obj.offsetWidth);
                    var hh = parseInt(obj.style.height||obj.offsetHeight);
                    var ccnntt = parseInt(cnt.style.height||cnt.offsetHeight);
                    var x0 = e.clientX;
                    var y0 = e.clientY;
                    var f1 = function(e1){
                        e1 = e1 || window.event;
                        if(state == 1){
                            e.preventDefault();
                            var x1 = e1.clientX;
                            var y1 = e1.clientY;
                            if(x1 < 0){
                                x1 = 0;
                            }else if(x1 > window.innerWidth){
                                x1 = window.innerWidth;
                            }
                            if(y1 < 0){
                                y1 = 0;
                            }else if(y1 > window.innerHeight){
                                y1 =  window.innerHeight;
                            }

                            obj.style.width=cnt.style.width=(ww+x1-x0)+'px';
                            obj.style.height=(hh+y1-y0)+'px';
                            cnt.style.height=(ccnntt+y1-y0)+'px';
                        }
                    }
                    var f2 = function(e2){
                        state = 0;
                        dom.removeEvent(document,"mousemove",f1);
                        dom.removeEvent(document,"mouseup",f2);
                    }
                    document.onmousemove = f1;
                    document.onmouseup = f2;
                }
            }
            obj.style.display = 'block';
            if(btnArr.length > 0){
                var minWidth = 0;
                for (var i = 0; i < btnArr.length; i++) {
                    minWidth += dom.getW(btnArr[i]);
                }
                var p1 = parseInt(dom.css(btnBox,'paddingLeft'));
                var p2 = parseInt(dom.css(btnBox,'paddingRight'));
                // var wm = dom.getW(btnBox);
                obj.style.minWidth = (minWidth+p1+p2 + btnArr.length*8+5)+'px';
                cnt.style.minWidth = (minWidth+p1+p2 + btnArr.length*8+5)+'px';
            }
            function setPos(){
                var winW = document.documentElement.clientWidth;
                var winH = document.documentElement.clientHeight;
                //offsetWidth获取只能是显示的元素
                var rw = parseInt(dom.getW(obj));
                var rh = (parseInt(dom.getH(obj)))+42+45;
                obj.style.top = (winH/2-rh/2)+'px';
                obj.style.left = (winW/2-rw/2)+'px';
            }
            setPos();
            clo.onclick=function(){
                dom.remove(shadowDiv);
                dom.remove(obj);
                if(zUI.util.isF(close)){
                    close();
                }
            };
            window.onresize=setPos;
        },
        alert:function(content,title){
            var set = {
                content: content,
                title: title,
                width: '300px',
                btns: {
                    '确定':function(){}
                },
            }
            this.alertUI(set);
        },
        alertDom: function (set) {
            var dom = zUI.dom;
            var target = dom.get(set.target);
            if(target){
                var parentNode = target.parentNode;
                var id = "_" + new Date().getTime()+"_"+zUI.util.UUID();
                var temp = dom.createElem({name:'div',id:id,clazz:'zUI-hide'});
                var newTar = target.cloneNode(true);
                try {
                    var oldClose = set.close;
                    set.close = function(){
                        dom.addClass(newTar,'zUI-hide');
                        parentNode.replaceChild(newTar,temp);
                    }
                    if(zUI.util.isF(oldClose)){
                        oldClose();
                    }
                    parentNode.replaceChild(temp,target);
                    dom.removeClass(newTar,'zUI-hide');
                    set.content = newTar.outerHTML;

                    this.alertUI(set);
                }catch (e) {
                    console.error(e);
                    parentNode.replaceChild(target,temp);
                    dom.addClass(target,'zUI-hide');
                }

            }
        },
        hoverTip: function (set) {
            var dom = zUI.dom;
            var dir = set.dir || 'left';
            var items = dom.gets(set.elem);
            if(items){
                var loadOne = function(index,obj){
                    var title = obj.getAttribute('zUI-title');
                    var stay = obj.getAttribute('zUI-title-stay')||'false'+'';
                    if(title){
                        var s1 = '#function:';
                        if(title.indexOf(s1)===0 && title.lastIndexOf('#')===title.length - 1){
                            var f = title.substring(10,title.length - 1);
                            title = eval(f+'('+obj+')');
                            if(typeof title !== 'string'){
                                title = title.outerHTML;
                            }
                        }
                        var id = 'zUI_tip_' + zUI.util.UUID();
                        obj.onmouseover = function(e){
                            var tip = dom.get('#'+id);
                            if(tip != null){
                                dom.remove(tip);
                                return;
                            }
                            tip = dom.createElem({name:'div',clazz:'zUI-tip',html:title,id:id});
                            var x,y,af;
                            dom.addClass(tip,dir);
                            var doLeft,doRight,doUp,doDown;
                            doLeft = function(){
                                x = e.target.offsetLeft;
                                y = e.target.offsetTop + dom.getH(e.target)/2;
                                af = function(){
                                    var dx = x-dom.getW(tip)-2;
                                    tip.style.top = (y-dom.getH(tip)/2) + 'px';
                                    tip.style.left = (dx) + 'px';
                                    if(dx < 0){
                                        tip.style.left = '0px';
                                        tip.style.width = x+'px';
                                        tip.style.top = (y-dom.getH(tip)/2) + 'px';

                                    }
                                }
                            }
                            doRight = function(){
                                x = e.target.offsetLeft + dom.getW(e.target)+2;
                                y = e.target.offsetTop + dom.getH(e.target)/2;
                                af = function(){
                                    tip.style.top = (y-dom.getH(tip)/2) + 'px';
                                    tip.style.left = (x-2) + 'px';
                                    var x1 = x + dom.getW(tip);
                                    var dx = dom.getW(document.body) - x1;
                                    if(dx < 0){
                                    }
                                }
                            }
                            doUp = function(){
                                x = e.target.offsetLeft + dom.getW(e.target)/2;
                                y = e.target.offsetTop - 2;
                                af = function(){
                                    tip.style.top = (y - dom.getH(tip)) + 'px';
                                    tip.style.left = (x-dom.getW(tip)/2-2) + 'px';
                                }
                            }
                            doDown = function(){
                                x = e.target.offsetLeft + dom.getW(e.target)/2;
                                y = e.target.offsetTop + dom.getH(e.target);
                                af = function(){
                                    tip.style.top = (y+ 2) + 'px';
                                    tip.style.left = (x-dom.getW(tip)/2-2) + 'px';
                                }
                            }
                            if(dir === 'left'){
                                doLeft();
                            }else if(dir === 'up'){
                                doUp();
                            }else if(dir === 'down'){
                                doDown();
                            }else{
                                doRight();
                            }
                            tip.style.left = x + 'px';
                            tip.style.top = y + 'px';
                            document.body.appendChild(tip);
                            af();
                            if(stay === 'false'){
                                obj.onmouseout = function(){
                                    dom.remove(tip);
                                }
                            }else{
                                obj.onclick = function(){
                                    dom.remove(tip);
                                }
                            }

                        }
                    }
                }
                zUI.util.each(items,loadOne);
            }
        },
        showMask: function (set) {
            var delay = set && set.delay || '';
            var maskCount = this.maskCount;
            this.maskCount = ++maskCount;
            var dom = zUI.dom;
            var mask = dom.get('.zUI-mask');
            if(mask == null){
                mask = dom.createElem({name:'div',clazz:'zUI-shadow zUI-mask'});
                mask.appendChild(dom.createElem({name:'img'}));
                document.body.appendChild(mask);
            }
            var that = this;
            if(delay && !isNaN(delay = parseInt(delay))){
                setTimeout(function () {
                    that.closeMask();
                },delay);
            }
        },
        closeMask: function (force) {
            var maskCount = this.maskCount;
            if(force === true){
                this.maskCount = 0;
            }else{
                this.maskCount = --maskCount;
            }
            if(maskCount <= 0){
                zUI.dom.remove('.zUI-mask');
            }
        },
    }
}());