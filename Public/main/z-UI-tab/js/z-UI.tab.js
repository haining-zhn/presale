;(function(){
    zUI.tab = {
        loadTab:function(set){
            var dom = zUI.dom;
            var elem = set.elem||'';
            var type = set.type||'1';
            var $obj = document.querySelectorAll(elem);
            var clicks = set.clicks||[];
            var that = this;
            var loadOne = function(tab,index){
                var navBox = tab.querySelector('.bar-box');
                var navs = navBox.querySelectorAll('.nav');
                var wraps = tab.querySelectorAll('.content .wrap');
                var minW = 0;
                for(var i=0;i<navs.length;i++){
                    var nav = navs[i];
                    minW += parseInt(dom.getW(nav));
                    var wrap = wraps.length > i ? wraps[i]:null;
                    var disable = dom.hasClass(nav,'disable');
                    if(!disable){
                        //闭包
                        nav.onclick = function(wp,nv,ind){
                            return function(e){
                                that.showTab(nv,wp,wraps,navs);
                                var myClick = clicks.length > ind ? clicks[ind]:null;
                                if(zUI.util.isF(myClick)){
                                    try{
                                        myClick(index,nv,wp);
                                    }catch (e) {
                                        console.error(e);
                                    }
                                }
                            }
                        }(wrap,nav,i);
                    }

                }
                tab.style.minWidth = ((navs.length+2)*5+minW+0.5)+'px';
            }
            for(var i=0;i<$obj.length;i++){
                loadOne($obj[i],i);
            }
        },
        switchTab: function (elem,index) {
            var dom = zUI.dom;
            var obj = document.querySelectorAll(elem);
            for (var i = 0; i < obj.length; i++) {
                if(dom.hasClass(obj[i],'zUI-bar')){
                    var navs = obj[i].querySelectorAll('.bar-box .nav');
                    var wraps = obj[i].querySelectorAll('.content .wrap');
                    var nav = navs.length > index ? navs[index] : null;
                    var wrap = wraps.length > index ? wraps[index]:null;
                    this.showTab(nav,wrap,wraps,navs);
                }
            }
        },
        showTab: function (nav,wrap,wraps,navs) {
            var dom = zUI.dom;
            if(nav != null){
                dom.removeClass(wraps,'zUI-show');
                dom.removeClass(navs,'active');
                dom.addClass(nav,'active');
                if(wrap != null){
                    dom.addClass(wrap,'zUI-show');
                }
            }
        },
        //加载一个面板
        loadPanel: function (set) {
            var dom = zUI.dom;
            var elem = set.elem||'';
            var $obj = document.querySelectorAll(elem);
            var close = set.close;
            var that = this;
            var loadOne = function(tab,index){
                var group = tab.getAttribute('zUI-card-group');
                var folder = tab.getAttribute('zUI-folder')||(group ? 'hide':'');

                var close = tab.hasAttribute('zUI-close');
                var title = tab.querySelector('.zUI-card-title');
                var bns = dom.createElem({name:'div',clazz:'btns'});
                title.appendChild(bns);
                var wrap = tab.querySelector('.zUI-card-wrap');
                var folderBtn = dom.createElem({name:'i',clazz:'zUI-icon icon-cancel-circle'});
                var show = folder === 'show';
                if(folder){
                    bns.appendChild(folderBtn);
                    dom.addClass(folderBtn,'zUI-icon ' + (show ? 'icon-circle-down':'icon-circle-left'));
                    dom.addClass(wrap,show?'zUI-show':'zUI-hide');
                    dom.removeClass(wrap,show?'zUI-hide':'zUI-show');
                    var hideCard = function(w,btn){
                        dom.removeClass(w,'zUI-show');
                        dom.addClass(btn,'icon-circle-left');
                        dom.addClass(w,'zUI-hide');
                    }
                    var showCard = function(w,btn){
                        dom.removeClass(w,'zUI-hide');
                        dom.addClass(btn,'icon-circle-down');
                        dom.addClass(w,'zUI-show');
                    }
                    var fod = function(e){
                        dom.removeClass(folderBtn,'icon-circle-down icon-circle-left');
                        var type = 'unknow';
                        if(dom.isVisable(wrap)){
                            hideCard(wrap,folderBtn);
                            type='hide';
                        }else{
                            showCard(wrap,folderBtn);
                            type='show';
                            if(group){
                                var others = tab.parentNode.querySelectorAll('.zUI-card[zUI-card-group=\''+group+'\']');
                                for (var i = 0; i < others.length; i++) {
                                    if(others[i] !== tab){
                                        var wrap2 = others[i].querySelector('.zUI-card-wrap');
                                        var folderBtn2 = others[i].querySelector('.zUI-card-title .btns .icon-circle-left,.icon-circle-down');
                                        hideCard(wrap2,folderBtn2);
                                    }
                                }

                            }

                        }
                        if(zUI.util.isF(set.change)){
                            try{
                                (set.change)(type);
                            }catch (e) {
                                console.error(e);
                            }
                        }
                    };
                    title.onclick = fod;

                }
                if(close){
                    var ico = dom.createElem({name:'i',clazz:'zUI-icon icon-cancel-circle'});
                    bns.appendChild(ico);
                    ico.onclick = function(){
                        var r = true;
                        if(zUI.util.isF(set.close)){
                            try{
                                r = (set.close)();
                            }catch (e) {
                                console.error(e);
                            }
                        }
                        if(r!== false){
                            dom.remove(this.parentNode.parentNode.parentNode);
                        }
                    }
                }
            }
            for(var i=0;i<$obj.length;i++){
                loadOne($obj[i],i);
            }
        }
    }
}());