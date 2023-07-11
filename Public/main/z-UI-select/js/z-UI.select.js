;(function(){
    zUI.select = {
        loadListTran:function(set){
            var dom = zUI.dom;
            var util = zUI.util;
            var elem = set.elem||'';
            var $obj = dom.gets(elem);
            var loadOne = function(tab){
                var left = tab.querySelector('.list-box');
                var right = tab.querySelectorAll('.list-box')[1];
                var leftUl = left.querySelector('.left');
                var rightUl = right.querySelector('.right');
                var optTran = dom.createElem({name:'div',clazz:'list-tool'});
                dom.insertAfter(optTran,left);
                var optTranDiv = dom.createElem({name:'div',clazz:'list-tool-indiv'});
                optTran.appendChild(optTranDiv);
                var optTranDivs = dom.createElem({name:'div'});
                optTranDiv.appendChild(optTranDivs);
                var ir = dom.createElem({name:'i',clazz:'zUI-icon icon-arrow-right2'});
                var iu = dom.createElem({name:'i',clazz:'zUI-icon icon-arrow-up2'});
                var id = dom.createElem({name:'i',clazz:'zUI-icon icon-arrow-down2'});
                var il = dom.createElem({name:'i',clazz:'zUI-icon icon-arrow-left2'});
                optTranDivs.appendChild(ir);
                optTranDivs.appendChild(iu);
                optTranDivs.appendChild(id);
                optTranDivs.appendChild(il);
                var goRight,goLeft,goUp,goDown;
                var click = function(){
                    if(dom.hasClass(this.parentNode,'left')){
                        dom.removeClass(rightUl.querySelectorAll('li.active'),'active');
                    }else{
                        dom.removeClass(leftUl.querySelectorAll('li.active'),'active');
                    }
                    if(dom.hasClass(this,'active')){
                        dom.removeClass(this,'active');
                    }else{
                        dom.addClass(this,'active');
                    }
                    setColor();
                    setColor2();
                }
                var setColor = function(){
                    var hasL = leftUl.querySelectorAll('li.active').length > 0;
                    var hasR = rightUl.querySelectorAll('li.active').length > 0;
                    if(!hasL){
                        dom.addClass(ir,'zUI-gray');
                    }else{
                        dom.removeClass(ir,'zUI-gray');
                    }
                    if(!hasR){
                        dom.addClass(il,'zUI-gray');
                    }else{
                        dom.removeClass(il,'zUI-gray');
                    }
                }
                var setColor2 = function(){
                    var lis = tab.querySelectorAll('li.active');
                    if(lis.length == 0){
                        dom.addClass(iu,'zUI-gray');
                        dom.addClass(id,'zUI-gray');
                    }else{
                        if(lis.length == 1 && lis[0].previousElementSibling == null){
                            dom.addClass(iu,'zUI-gray');
                        }else{
                            dom.removeClass(iu,'zUI-gray');
                        }
                        if(lis.length == 1 && lis[0].nextElementSibling == null){
                            dom.addClass(id,'zUI-gray');
                        }else{
                            dom.removeClass(id,'zUI-gray');
                        }
                    }

                }
                goRight =  function () {
                    var lis = leftUl.querySelectorAll('li.active');
                    if(lis.length > 0){
                        util.each(lis,function(i,o){
                            var newLi = o.cloneNode(true);
                            dom.remove(o);
                            rightUl.appendChild(newLi);
                            newLi.onclick = click;
                        });
                        rightUl.scrollTop = rightUl.offsetHeight;
                    }
                    setColor();
                    setColor2();
                }
                goLeft =  function () {
                    var lis = rightUl.querySelectorAll('li.active');
                    if(lis.length > 0){
                        util.each(lis,function(i,o){
                            var newLi = o.cloneNode(true);
                            dom.remove(o);
                            leftUl.appendChild(newLi);
                            newLi.onclick = click;
                        });
                        leftUl.scrollTop = leftUl.offsetHeight;
                    }
                    setColor();
                    setColor2();
                }
                goUp =  function () {
                    var lis = tab.querySelectorAll('li.active');
                    var hasL = leftUl.querySelectorAll('li.active').length > 0;
                    var hasR = rightUl.querySelectorAll('li.active').length > 0;
                    var lm = false,rm=false;
                    util.each(lis,function(i,o){
                        var pre = o.previousElementSibling;

                        if(pre != null){
                            var no = dom.swapNode(pre,o);
                            o.onclick = click;
                            no.onclick = click;
                            if(!lm&& hasL){
                                leftUl.scrollTop = leftUl.scrollTop-dom.getH(o);
                                lm = !lm;
                            }
                            if(!rm&& hasR){
                                rightUl.scrollTop = leftUl.scrollTop-dom.getH(o);
                                rm = !rm;
                            }
                        }
                    });
                    setColor2();
                }
                goDown =  function () {
                    var lis = tab.querySelectorAll('li.active');
                    var lm = false,rm=false;
                    var hasL = leftUl.querySelectorAll('li.active').length > 0;
                    var hasR = rightUl.querySelectorAll('li.active').length > 0;
                    for (var i = lis.length -1; i >= 0 ; i--) {
                        var nex = lis[i].nextElementSibling;
                        if(nex != null){
                            var o = dom.swapNode(nex,lis[i]);
                            lis[i].onclick = click;
                            nex.onclick = click;
                            o.onclick = click;
                            if(!lm&& hasL){
                                leftUl.scrollTop = leftUl.scrollTop+dom.getH(o);
                                lm = !lm;
                            }
                            if(!rm&& hasR){
                                rightUl.scrollTop = leftUl.scrollTop+dom.getH(o);
                                rm = !rm;
                            }
                        }
                    }
                    setColor2();
                }
                ir.onclick = goRight;
                il.onclick = goLeft;
                iu.onclick = goUp;
                id.onclick = goDown;
                var liList = tab.querySelectorAll('li');
                util.each(liList,function(i,o){
                    o.onclick = click;
                });
                setColor();
                setColor2();
            }
            util.each($obj,function(i,o){
                loadOne(o);
            });
        },
        getListTranData: function(elem){
            var obj = zUI.dom.get(elem);
            if(zUI.dom.hasClass(obj,'zUI-list-tran')){
                var lis = obj.querySelectorAll('.list-box .left li');
                var ris = obj.querySelectorAll('.list-box .right li');
                var l = [],r=[];
                zUI.util.each(lis,function(i,o){
                    l.push(o.getAttribute('list-tran-data'));
                });
                zUI.util.each(ris,function(i,o){
                    r.push(o.getAttribute('list-tran-data'));
                });
                return {l:l,r:r};
            }
            return null;
        }
    }
}());