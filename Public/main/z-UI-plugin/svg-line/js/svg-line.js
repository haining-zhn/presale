$.fn.extend({
    svgLine: function(set){
        var cnt = window.svgLineCount ? window.svgLineCount : window.svgLineCount=0;
        cnt = ++window.svgLineCount;
        var onSelect = set.onSelect||function(){},onCancle = set.onCancle||function(){};
        var doSelect = function(a,b){
            try {
                onSelect(a,b);
            }catch (e) {
                console.error(e);
            }
        },doCancle = function(a,b){
            try {
                onCancle(a,b);
            }catch (e) {
                console.error(e);
            }
        }
        var liSize = set.size||'sm';
        var removAble = set.removAble;
        var skin = set.skin||'green';
        if(['sm','md','lg'].indexOf(liSize) === -1){
            liSize = 'sm';
        }
        $(this).empty();
        function nvl(a,b){
            return a ==null || a==='' ? b : a;
        }
        var leftList = set.leftList,headerLeft = set.headerLeft;
        var rightList = set.rightList,headerRight = set.headerRight;
        var minWidth = "500px",mapping = set.mapping||{};
        var key = nvl(set.key,'key'),width = nvl(set.width,minWidth);
        var name = nvl(set.name,'name');
        var selectKey = '.lt-tr[sid='+cnt+'] ';
        function getSvgBox(dir){
            return $(selectKey+"."+dir);
        }
        function getSvgUli(dir,k){
            return $(selectKey+"."+dir+" li[key='"+k+"']");
        }
        $(this).append('<div style="'+(set.width ? 'width:'+width:'')+';min-width:'+minWidth+'"><div sid="'+cnt+'" class="lt-tr '+liSize+' '+skin+'"><div class="list-box left"><div class="list-header">'+headerLeft+'</div><ul class="lul"></ul></div><div class="list-box right"><div class="list-header">'+headerRight+'</div><ul class="rul"></ul></div></div><svg sid="'+cnt+'" xmlns="http://www.w3.org/2000/svg" version="1.1" style=""></svg></div>');

        var leftObj,rightObj = null;
        function synView(){
            setSvgSize();
            for (var x in mapping) {
                drawLine(x,mapping[x]);
            }
        }
        var mx = 0,my = 0;
        function setSvgSize(){
            var left = getSvgBox('left');
            var right = getSvgBox('right');
            var x1 = left.offset().left+ left.outerWidth(),x2 = right.offset().left;
            var y1 = left.offset().top,y2 = right.offset().top ;
            var svgWidth,svgHeight ;
            svgWidth = (x1 >= x2) ? 0:x2-x1;
            if(y1 <= y2){
                svgHeight = y2-y1 + right.outerHeight();
            }else{
                svgHeight = y1-y2 + left.outerHeight();
            }
            $("svg[sid="+cnt+"]").width(svgWidth).height(svgHeight);
            return {};
        }
        function canEdit(c1,c2){
            var llt = c1 == null ? false : getSvgUli('lul',c1).hasClass('disabled');
            var rrt = c2 == null ? false : getSvgUli('rul',c2).hasClass('disabled');
            return !(llt || rrt);
        }
        function setActive(){
            if($(this).hasClass('disabled')){
                return;
            }
            var key = $(this).attr('key');
            var bool = $(this).parent().hasClass("lul");
            var mm = inMapping(key,bool);
            if(mm !== false){
                if(!canEdit(mm[0],mm[1])){
                    return;
                }
                removeMapping(mm[0]);
                if(mm[0]!=='drea_temp_mark' && mm[1] !=='drea_temp_mark')
                    doCancle(mm[0],mm[1]);
            }
            if(bool){
                if($(this).hasClass("active")){
                    getSvgUli('lul',leftObj).removeClass("active");
                    leftObj = null;
                }else{
                    if(leftObj != null){
                        getSvgUli('lul',leftObj).removeClass("active");
                        removeMapping('drea_temp_mark');
                        $("line[id*=drea_temp_mark]").remove();
                    }
                    leftObj = key;
                    getSvgUli('lul',leftObj).addClass("active");
                    mapping[key]='drea_temp_mark';
                }
            }else{
                if($(this).hasClass("active")){
                    getSvgUli('rul',rightObj).removeClass("active");
                    rightObj = null;
                }else{
                    if(rightObj != null){
                        getSvgUli('rul',rightObj).removeClass("active");
                        removeMapping('drea_temp_mark');
                        $("line[id*=drea_temp_mark]").remove();
                    }
                    rightObj = key;
                    getSvgUli('rul',rightObj).addClass("active");
                    mapping['drea_temp_mark']=key;
                }

            }
            if(leftObj != null && rightObj != null){
                drawLine(leftObj,rightObj);
                removeMapping('drea_temp_mark');
                $("line[id*=drea_temp_mark]").remove();
                getSvgUli('lul',leftObj).addClass("active");
                getSvgUli('rul',rightObj).addClass("active");
                if(leftObj!=='drea_temp_mark' && rightObj !=='drea_temp_mark')
                    try {
                        doSelect(leftObj,rightObj);
                    }catch (e) {
                        console.error(e);
                    }

                leftObj =rightObj= null;
            }
        }
        function removeMapping(c) {
            for (var x in mapping) {
                var ano = mapping[x];
                var b1 = c === x ,b2=mapping[x]===c;
                if(b1 || b2){
                    delete mapping[x];
                    //$("svg[sid="+cnt+"] #line_"+cnt+"_"+x+"_"+ano).remove();
                    removeLineObj(x,ano);
                    //$(".lt-tr[sid="+cnt+"] .lul li[key='"+x+"']").removeClass("active");
                    //$(".lt-tr[sid="+cnt+"] .rul li[key='"+ano+"']").removeClass("active");
                    return [x,ano];
                }
            }
            return null;
        }
        function removeLineObj(l,r){
            $("svg[sid="+cnt+"] #line_"+cnt+"_"+l+"_"+r).remove();
            $("svg[sid="+cnt+"] #line_"+cnt+"_"+l+"_"+r+"_l").remove();
            $("svg[sid="+cnt+"] #line_"+cnt+"_"+l+"_"+r+"_r").remove();
            getSvgUli('lul',l).removeClass("active");
            getSvgUli('rul',r).removeClass("active");
        }
        function inMapping(c,isLeft) {
            for (var x in mapping) {
                if((isLeft && c === x) || (!isLeft && mapping[x]===c)){
                    return [x,mapping[x]];
                }
            }
            return false;
        }

        function addItem(ul,item){
            var title = nvl(item[name],item[key]);
            var icon = item.icon||"";
            if(icon !== ""){
                icon = "<i class='"+icon+"'></i>"
            }
            var disable = !!item.disable;
            var removable = item.removable!== undefined ? item.removable : removAble!== false;
            var html = '<span>'+icon+title+'</span>';


            //添加选项时绑定事件
            var ldd=$("<li key='"+item[key]+"' title='"+title+"'" +(disable?" class='disabled'":"")+ "><div>"+html+"</div></li>").appendTo($(ul)).click(setActive).mousemove(function(e){
                var li = $(e.target);
                if(li.is('span')){
                    li=li.parent().parent();
                }
                var l = li.parent().hasClass('lul');
                var l1 = leftObj == null;
                var l2 = rightObj == null;
                var left = getSvgBox('left');
                var right = getSvgBox('right');
                if(l && l1 && !l2){
                    mx = 0,my=li.offset().top - left.offset().top +li.outerHeight()/2;
                    drawTempLine('drea_temp_mark',rightObj);
                }
                if(!l && !l1 && l2){
                    mx = $("svg[sid="+cnt+"]").width(),my=li.offset().top - left.offset().top +li.outerHeight()/2;
                    drawTempLine(leftObj,'drea_temp_mark');
                }

            });
            if(removable){
                //ldd.addClass("removable");
                $('<span class="close">×</span>').click(function(){
                    if(leftObj==null&&rightObj == null){
                        var lit = $(this).parent().parent();
                        var kk = lit.attr('key');
                        var bool = lit.parent().hasClass("lul");
                        var mm = inMapping(kk,bool);
                        var left,right;
                        if(mm !== false){
                            if(!canEdit(mm[0],mm[1])){
                                return;
                            }
                            delete mapping[mm[0]];
                            removeLineObj(mm[0],mm[1]);
                            getSvgUli(bool?'lul':'rul',kk).remove();
                            doCancle(mm[0],mm[1]);
                        }else{
                            if(bool && !canEdit(kk,null)){
                                return;
                            }
                            if(!bool && !canEdit(null,kk)){
                                return;
                            }
                            getSvgUli(bool?'lul':'rul',kk).remove();
                        }
                        synView();
                    }


                }).appendTo(ldd.children('div'));
            }
        }
        $(document).bind('mousedown',function(e){
            var obj = $(e.target);
            if(!obj.is('li[key]') &&!obj.is('li[key] div') && !obj.is('li[key] i') && !obj.is('li[key] span') && !obj.is('line:not([id*=drea_temp_mark])')){
                if(leftObj != null){
                    getSvgUli('lul',leftObj).removeClass("active");
                }
                if(rightObj != null){
                    getSvgUli('rul',rightObj).removeClass("active");
                }
                removeMapping('drea_temp_mark');
                $("line[id*=drea_temp_mark]").remove();
                leftObj = null;
                rightObj = null;
            }
        });
        function drawLine(c1,c2){
            if(c1 === 'drea_temp_mark' || c2 === 'drea_temp_mark'){
                return;
            }
            var left = getSvgBox('left');
            var right = getSvgBox('right');
            var svg = $("svg[sid="+cnt+"]");
            var li1 = getSvgUli('lul',c1);
            var li2 = getSvgUli('rul',c2);
            li1.addClass("active");
            li2.addClass("active");
            var x1 = li1.offset().left,x2 = li2.offset().left;
            var y1 = li1.offset().top,y2 = li2.offset().top;
            var id = "line_"+cnt+"_"+c1+"_"+c2;
            var cir1Id = id+"_l",cir2Id = id+"_r";
            var line = $("svg[sid="+cnt+"] #"+id);
            var cir1 = $("svg[sid="+cnt+"] #"+cir1Id);
            var cir2 = $("svg[sid="+cnt+"] #"+cir2Id);
            if(line.length == 0){
                line = $(makeSVG('line',id));
                line.click(function(){
                    //console.log(c1,c2);
                    if(!canEdit(c1,c2)){
                        return;
                    }
                    delete mapping[c1];
                    removeLineObj(c1,c2);
                    doCancle(c1,c2);
                    if(leftObj!= null){
                        //console.log(2);
                        mapping[leftObj] = c2;
                        doSelect(leftObj,c2);
                        leftObj=rightObj=null;
                    }else if(rightObj != null){
                        //console.log(3);
                        mapping[c1] = rightObj;
                        doSelect(c1,rightObj);
                        leftObj=rightObj=null;
                    }
                    removeMapping('drea_temp_mark');
                    $("line[id*=drea_temp_mark]").remove();
                    synView();
                });
                cir1 = $(makeSVG('circle',cir1Id));
                cir2 = $(makeSVG('circle',cir2Id));
                svg.append(line).append(cir1).append(cir2);
                mapping[c1] = c2;
            }
            var xxx1 = 0,xxx2 = svg.width();


            var yyy1 = y1 - svg.offset().top + li1.outerHeight()/2;
            var yyy2 = y2 - svg.offset().top + li2.outerHeight()/2;
            /*
            */

            var hh = 30,loft ,roft ;
            if(left.offset().top<right.offset().top){
                loft = hh;
                roft = right.offset().top - left.offset().top + hh;
            }else{
                loft = left.offset().top - right.offset().top + hh;
                roft = hh;
            }
            var ofl1 = false,ofl2 = false;
            if(yyy1 <= loft){
                yyy1 = loft,ofl1=true;
            }
            if(yyy1 > loft + left.outerHeight() - hh){
                yyy1 = loft + left.outerHeight() - hh,ofl1=true;
            }
            if(yyy2 <= roft){
                yyy2 = roft,ofl2=true;
            }
            if(yyy2 > roft + right.outerHeight() - hh){
                yyy2 = roft + right.outerHeight() - hh,ofl2=true;
            }
            line.attr("x1",xxx1).attr("x2",xxx2).attr("y1",yyy1).attr("y2",yyy2);
            if(ofl1){
                cir1.hide();
            }else{
                cir1.show();
            }if(ofl2){
                cir2.hide();
            }else{
                cir2.show();
            }
            cir1.attr("cx",xxx1).attr("cy",yyy1).attr("r",5);
            cir2.attr("cx",xxx2).attr("cy",yyy2).attr("r",5);
        }
        function drawTempLine(c1,c2){
            var b = c1==='drea_temp_mark';
            var left = getSvgBox('left');
            var right = getSvgBox('right');
            var svg = $("svg[sid="+cnt+"]");
            var li1 = getSvgUli('lul',c1);
            var li2 = getSvgUli('rul',c2);
            var ht = b ? li2.outerHeight()/2 : li1.outerHeight()/2;
            li1.addClass("active");
            li2.addClass("active");
            var svgTop = svg.offset().top;
            var svgLeft = svg.offset().top;
            var x1 = b ? mx : li1.offset().left;
            var x2 = b ? li2.offset().left : mx;
            var y1 = b ? my : li1.offset().top;
            var y2 = b ? li2.offset().top : my;
            var id = "line_"+cnt+"_"+c1+"_"+c2;
            var line = $("svg[sid="+cnt+"] #"+id);
            if(line.length == 0){
                line = $(makeSVG('line',id));
                svg.append(line);
            }
            var xxx1 = b ? mx+1 : 0;
            var xxx2 = b ? svg.width() : mx-2;
            var yyy1 = b ? my : y1 - svg.offset().top + ht;
            var yyy2 = b ? y2 - svg.offset().top + ht : my;
            var hh = 30;
            var loft ,roft ;
            if(left.offset().top<right.offset().top){
                loft = hh;
                roft = right.offset().top - left.offset().top + hh;
            }else{
                loft = left.offset().top - right.offset().top + hh;
                roft = hh;
            }
            if(yyy1 <= loft){
                yyy1 = loft;
            }
            if(yyy1 > loft + left.outerHeight() - hh){
                yyy1 = loft + left.outerHeight() - hh;
            }
            if(yyy2 <= roft){
                yyy2 = roft;
            }
            if(yyy2 > roft + right.outerHeight() - hh){
                yyy2 = roft + right.outerHeight() - hh;
            }
            line.attr("x1",xxx1).attr("x2",xxx2).attr("y1",yyy1).attr("y2",yyy2);
        }
        function makeSVG(tag,id) {
            var ns = 'http://www.w3.org/2000/svg';
            var el= document.createElementNS(ns, tag);
            el.setAttribute("class","draw-line");
            el.setAttribute("id",id);
            return el;
        }
        $(selectKey+".lul").bind('scroll',synView);
        $(selectKey+".rul").bind('scroll',synView);
        for (var i = 0 ;i<leftList.length;i++){
            addItem(selectKey+".lul",leftList[i]);
        }
        for (var i = 0 ;i<rightList.length;i++){
            addItem(selectKey+".rul",rightList[i]);
        }
        synView();
        $(window).resize(synView);
        $("svg[sid="+cnt+"]").bind('mousemove',function(e){
            mx = e.offsetX;
            my = e.offsetY;
            var l1 = leftObj == null ? 'drea_temp_mark' : leftObj;
            var l2 = rightObj == null ? 'drea_temp_mark' : rightObj;
            if(leftObj != null && rightObj == null || leftObj == null && rightObj != null){
                drawTempLine(l1,l2);
            }
        });
        return {
            getData:function(){
                return mapping;
            },
            setData:function(m){
                mapping = m;
                $('.lt-tr[sid='+cnt+'] li[key]').removeClass('active');
                $("svg[sid="+cnt+"]").empty();
                synView();
            },
            resize:function(w){
                if(w){
                    $(".lt-tr[sid="+cnt+"]").parent().width(w);
                }
                synView();
            },
            refresh:synView,
            addOptions:function(opt){
                if(opt){
                    var lts = opt.leftList;
                    var rts = opt.rightList;
                    var ids = [];
                    $(selectKey+".lul li").each(function(i,o){
                        ids.push($(o).attr("key"));
                    });
                    if(lts){
                        for (var i in lts) {
                            if(ids.indexOf(lts[i][key])===-1){
                                addItem(selectKey+".lul",lts[i])
                            }
                        }
                    }
                    if(rts){
                        for (var i in rts) {
                            if(ids.indexOf(rts[i][key])===-1){
                                addItem(selectKey+".rul",rts[i])
                            }
                        }
                    }
                }
            },
            deleteOptions: function(opt){
                if(opt){
                    var lts = opt.leftList;
                    var rts = opt.rightList;
                    if(lts){
                        for (var i in lts) {
                            var ttt = mapping[lts[i]];
                            if(ttt){
                                delete mapping[lts[i]];
                                removeLineObj(lts[i],ttt);
                            }
                            $(selectKey+".lul li[key='"+lts[i]+"']").remove();
                        }
                    }
                    if(rts){
                        var rtlst = {};
                        for (var mappingKey in mapping) {
                            rtlst[mapping[mappingKey]] = mappingKey;
                        }
                        for (var i in rts) {
                            var ttt = rtlst[rts[i]];
                            if(ttt){
                                delete mapping[ttt];
                                removeLineObj(ttt,rts[i]);
                                $(selectKey+".rul li[key='"+rts[i]+"']").remove();
                            }
                        }
                    }
                }
            },
            getUl: function () {
                return {
                    left:$(selectKey+".lul"),
                    right:$(selectKey+".rul")
                };
            }
        }
    }
});
