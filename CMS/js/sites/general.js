var bob = {
    statics     : {
        keyboard    : {
            allowedSystemCombinations : [
                [9],
                [48, -16],
                [65, -16],
                [67, -16],
                [68, -16],
                [70, -16],
                [78, -16],
                [82, -16],
                [84, -16],
                [86, -16],
                [87, -16],
                [88, -16],
                [89, -16],
                [90, -16],
                [107, -16],
                [109, -16],
                [116, -16],
                [16, 17, 37],
                [16, 17, 38],
                [16, 17, 39],
                [16, 17, 13],
                [16, 17, 46]
            ]
        },
        names      : {
            saveBtn : {
                save                        : true,
                Save                        : true,
                'Save &amp; Close'          : true,
                'Save All Modifications'    : true,
                'Upload CSV file'           : true,
                'Save as new revision'      : true
            },
            datefields : [
                'created_date', 'updated_date', 'start_date', 'end_date', 'from_date', 'to_date', 'date'
            ]
        }
    },
    state       : {
        keys            : {
            size            : function() {
                counter = 0;
                $.each(bob.state.keys, function() {
                    counter++;
                });
                return counter-1;
            }
        },
        visibility      : {
            gridKeyHint     : false
        },
        restoreValues : {
            'default' : null,
            current : null
        },
        page : {
            module : null,
            controller : null,
            action : null,
            documentation : null,
        },
        mouse : {
            control : {
                slider : {
                    on : false,
                    getIndex : function() {
                        return bob.state.mouse.control.slider.select.children('option[value="'+bob.state.mouse.control.slider.select.val()+'"]').index();
                    }
                }
            }
        }
    },
    services    : {
        workspace   : function() {
            if ($(window).height() < $(document).height()) {
                bob.actions.document.general.visibility($("#closing"),false,'hidden');
            } else {
                bob.actions.document.general.visibility($("#closing"),true,'hidden');
            }
            window.setTimeout(function() {bob.services.workspace();}, 5000);
        }
    },
    actions     : {
        registerKey         : function(key) {
            if (typeof(key) == "undefined") {
                key = event;
            }
            if (!bob.state.keys[key.keyCode]) {
                bob.state.keys[key.keyCode] = true;
                //console.log(key.keyCode);
            }
            bob.actions.checkKeyRegistry(true, key);
            key = bob.actions.keyboard.returnState(key);
            return key.returnValue;
        },
        unregisterKey       : function(key) {
            if (typeof(key) == "undefined") {
                key = event;
            }
            bob.actions.checkKeyRegistry(false, key);
            key = bob.actions.keyboard.returnState(key);
            delete bob.state.keys[key.keyCode];
            return key.returnValue;
        },
        checkKeyRegistry    : function(hintShortcuts, key) {
            if (!key.altKey) {
                if (!hintShortcuts) {
                    if (bob.state.keys[17] && !bob.state.keys[16]) {
                        bob.actions.keyboard.navigationShortcut();
                    }
                    if (bob.state.keys[17] && bob.state.keys[16]) {
                        bob.actions.document.navigation.toggleGridKeyHint(false);
                    }
                    if (bob.state.keys[17] && bob.state.keys[83]) {
                        bob.actions.document.navigation.submitActiveForm($(key.target));
                    }
                    if (bob.state.keys[27]) {
                        bob.actions.document.general.hideOpenComponents();
                    }
                    if (bob.state.keys[13]) {
                        if ($(".modalDialog:visible").length > 0) {
                            $(".modalDialog:visible .form_button.ok").click();
                        }
                    }
                } else {
                    if (bob.state.keys[17] && bob.state.keys[16]) {
                        if (bob.state.visibility.gridKeyHint) {
                            bob.actions.keyboard.gridNavigationShortcut();
                        }
                        bob.actions.document.navigation.toggleGridKeyHint(true);
                    }
                }
            }
        },
        keyboard            : {
            navigationShortcut  : function() {
                for (var i = 49; i < 58; i++) {
                    if (bob.state.keys[i]) {
                        if ($("#navigation > .item .subMenu:visible").length > 0) {
                            bob.actions.document.navigation.openSubmenuLink(i-49);
                        } else {
                            bob.actions.document.navigation.openSubmenu(i-49);
                        }
                    }
                }
            },
            gridNavigationShortcut : function() {
                target = false;
                for (var i = 49; i < 58; i++) {
                    if (bob.state.keys[i]) {
                        target = i-49;
                    }
                }
                for (i = 65; i < 91; i++) {
                    if (bob.state.keys[i]) {
                        target = i-56;
                    }
                }

                if (target !== false) {
                    el = $("table.grid.linked tbody tr:nth-of-type("+(target + 1)+") a:first-child");
                    if (typeof(el) != "undefined") {
                        el.click();
                    }
                }
            },
            returnState     : function(key) {
                if (bob.state.keys[17] && !key.altKey) {
                    if (bob.state.keys.size() > 1) {
                        var success;
                        $.each(bob.statics.keyboard.allowedSystemCombinations, function() {
                            success = [false,true];
                            $.each(this, function(i, keyCode) {
                                if (keyCode < 0) {
                                    if (bob.state.keys[keyCode * (-1)]) {
                                        success[1] = false;
                                    }
                                } else {
                                    if (!bob.state.keys[keyCode]) {
                                        success[1] = false;
                                    }
                                }
                            });
                            if (success[1]) {
                                success[0] = true;
                                return false;
                            } else {
                                return true;
                            }
                        });
                        if(success[0]) {
                            key.returnValue = true;
                        } else {
                            key.cancelBubble = true;
                            key.returnValue = false;
                        }
                    } else {
                        key.returnValue = true;
                    }
                } else {
                    key.returnValue = true;
                }
                return key;
            }
        },
        document            : {
            general             : {
                hideOpenComponents  : function() {
                    if ($("#navigation > .item .subMenu:visible").length > 0) {
                        $("#navigation > .item .subMenu:visible").hide();
                    }
                    if ($(".modalDialog:visible").length > 0) {
                        $(".modalDialog:visible").parent().hide().prev('.layover').hide();
                    }
                    if ($('#cms_final_preview').is(':visible')) {
                        cms.document.content.events.revisionPreview.hide();
                    }
                },
                visibility          : function(object, state, className) {
                    if (typeof(className) == "undefined") {
                        className = false;
                    }
                    switch (state) {
                        case false  :
                            if (className) {
                                object.removeClass(className);
                            } else {
                                object.hide();
                            }
                            break;
                        case true   :
                            if (className) {
                                object.addClass(className);
                            } else {
                                object.show();
                            }
                    }
                },
                datefields  : function() {
                    $.each($('.zend_form input.datepicker, input[name*='+bob.statics.names.datefields.join('][type=text], input[name*=')+'][type=text]'), function() {
                        if ($(this).hasClass('no_datepicker')) {
                            return true;
                        }
                        var configArray = {};
                        if (this.defaultValue) {
                            bob.state.restoreValues['default'] = this.defaultValue;
                        }
                        if ($(this).val()) {
                            bob.state.restoreValues.current = $(this).val();
                        }
                        this.defaultValue = '';
                        $(this).val('');
                        if ($(this).hasClass('datepicker_time')) {
                            configArray = {
                                format: 'Y-m-d 00:00:00'
                            };
                        }
                        $(this).Zebra_DatePicker(configArray);
                        this.defaultValue = bob.state.restoreValues['default'];
                        $(this).val(bob.state.restoreValues.current);
                        if (!($(this).hasClass('read_only'))) {
                            $(this).removeAttr('readonly');
                        } else {
                            $(this).click(function() {
                                $(this).next('button.Zebra_DatePicker_Icon').click();
                            });
                        }
                    });
                },
                rangesliders : function() {
                    var slidersExisting = false;
                    $('select.rangeslider').each(function() {
                        slidersExisting = true;
                        var $btn = $('<div class="btn"></div>');
                        $btn.mousedown(function(event) {
                            bob.state.mouse.control.slider.on = true;
                            bob.state.mouse.control.slider.obj = $btn;
                            bob.state.mouse.control.slider.select = $btn.parent().prev('.rangeslider');
                            bob.state.mouse.control.slider.length = bob.state.mouse.control.slider.select.children('option').length;
                            $btn.addClass('active');
                            $(document.body).addClass('active');
                            event.stopPropagation();
                        });

                        var $rangesliderBody = $('<div class="rangeslider_body"></div>');
                        $rangesliderBody.append($btn);

                        $(this)
                            .after('<div class="rangeslider_label"><div class="left">'+$(this).attr('legendstart')+'</div>'
                                +'<div class="right">'+$(this).attr('legendstop')+'</div><div class="clear"></div></div>')
                            .after($rangesliderBody);
                        $(this).removeAttr('legendstart').removeAttr('legendstop');

                        bob.state.mouse.control.slider.obj = $btn;
                        bob.state.mouse.control.slider.select = $btn.parent().prev('.rangeslider');
                        bob.state.mouse.control.slider.length = bob.state.mouse.control.slider.select.children('option').length;
                        bob.actions.document.general.updateRangesliderPosition(0, bob.state.mouse.control.slider.getIndex());
                    });
                    if (slidersExisting) {
                        $('body').mousemove(function(event) {
                            bob.actions.document.general.rangesliderMove(event);
                            event.stopPropagation();
                        });
                        $('body').mouseup(function(event) {
                            bob.state.mouse.control.slider.on = false;
                            event.stopPropagation();
                            bob.state.mouse.control.slider.obj.removeClass('active');
                            $(document.body).removeClass('active');
                        });
                    }
                },
                rangesliderMove : function(event) {
                    if (bob.state.mouse.control.slider.on) {
                        var offset_x = bob.state.mouse.control.slider.obj.parent().offset().left + 9;
                        var percentage = 0;
                        if (event.pageX - offset_x !== 0) {
                            percentage = (event.pageX - offset_x) / (bob.state.mouse.control.slider.obj.parent().width() - 17);
                            if (percentage < 0) {
                                percentage = 0;
                            }
                            if (percentage > 1) {
                                percentage = 1;
                            }
                        }
                        bob.actions.document.general.updateRangesliderPosition(percentage);
                    }
                },
                updateRangesliderPosition : function(percentage, index) {
                    var obj = bob.state.mouse.control.slider.obj;

                    if (typeof index !== 'undefined') {
                        percentage = index / (bob.state.mouse.control.slider.length - 1);
                        $(bob.state.mouse.control.slider.select.children('option').get(index)).attr('selected', 'selected');
                    } else {
                        var rangeArr = [];
                        for (i = 0; i < bob.state.mouse.control.slider.length; i++) {
                            rangeArr.push(i);
                        }
                        var distance = [
                            null,
                            null
                        ];
                        $.each(rangeArr, function(key) {
                            var currPerc = key / (bob.state.mouse.control.slider.length - 1);
                            var currDist = Math.abs(percentage - currPerc);
                            if (distance[0] === null || distance[0] >= currDist) {
                                distance = [currDist, key];
                            }
                        });
                        percentage = distance[1] / (bob.state.mouse.control.slider.length - 1);
                        $(bob.state.mouse.control.slider.select.children('option').get(distance[1])).attr('selected', 'selected');
                    }

                    var newPosition = ((obj.parent().width() - 17) * percentage) + 3;
                    obj.css('left', newPosition);
                },
                interactivateInputs : function(domSelector) {
                    $(domSelector).change(function() {
                        if ($(this).val() == this.defaultValue || $(this).val() == "") {
                            $(this).removeClass("active");
                        } else {
                            $(this).addClass("active");
                        }
                    });
                    $(domSelector).focus(function() {
                        if ($(this).val() == this.defaultValue) {$(this).val("");}
                        this.select();
                    });
                    $(domSelector).blur(function() {
                        if ($(this).val() == "") {$(this).val(this.defaultValue);}
                    });
                },
                interactivateSwitches : function(domSelector) {
                    $(domSelector).click(function() {
                        $(this).toggleClass('active');
                        formEl = $(this).prev('input');
                        if ($(this).hasClass('active')) {
                            formEl.val('1');
                        } else {
                            formEl.val('0');
                        }
                    });
                },
                toggleGridRow : function(obj) {
                    var input = obj.parent().next('input');
                    if (input.is(':checked')) {
                        input.removeAttr('checked').val('0');
                        obj.parents('table.grid.selectable tr').removeClass('selected');
                    } else {
                        input.attr('checked','checked').val('1');
                        obj.parents('table.grid.selectable tr').addClass('selected');
                    }
                    bob.actions.document.general.updateToggleAllIcon(obj);
                },
                toggleGridRows : function(obj) {
                    var $toggleRows = obj.parents('table.grid.selectable').find('tbody tr');
                    if (obj.hasClass('checked')) {
                        $toggleRows.removeClass('selected');
                        $toggleRows.children('td:first-child').children('input[type=checkbox]').removeAttr('checked').val('0');
                    } else {
                        $toggleRows.addClass('selected');
                        $toggleRows.children('td:first-child').children('input[type=checkbox]').attr('checked','checked').val('1');
                    }
                    bob.actions.document.general.updateToggleAllIcon(obj);
                },
                updateToggleAllIcon : function(obj) {
                    var gridRowState = bob.actions.document.general.getGridRowState();
                    var $toggleIcon = obj.parents('table.grid.selectable').find('.toggleAll');
                    if (gridRowState.unselected.length == 0) {
                       $toggleIcon.addClass('checked').removeClass('unchecked semichecked');
                    } else {
                        if (gridRowState.selected.length == 0) {
                            $toggleIcon.addClass('unchecked').removeClass('checked semichecked');
                        } else {
                            $toggleIcon.addClass('semichecked').removeClass('checked unchecked');
                        }
                    }
                },
                getGridRowState : function() {
                    var state = {
                        selected : [],
                        unselected : []
                    };
                    $('table.grid.selectable input[name*=selected_row]').each(function() {
                        var identifier = $(this).attr('name').substr(14,$(this).attr('name').length-15);
                        if ($(this).val() == '0') {
                            state.unselected.push(identifier);
                        } else {
                            state.selected.push(identifier);
                        }
                    });
                    return state;
                },
                pushToIconTray : function(className, callback, href) {
                    className = 'ico' + className.charAt(0).toUpperCase() + className.substr(1);
                    var $newIcon;
                    if (!callback) {
                        $newIcon = $('<a href="' + href + '" class="item ico ' + className + '"></a>');
                    } else {
                        $newIcon = $('<span class="item ico ' + className + '" onclick="' + callback + '"></span>');
                    }
                    $newIcon.hide();
                    var trayIcon = $('#iconTray').width();
                    var newWidth = trayIcon * 2;
                    $('#iconTray').attr('style','width:'+newWidth + 'px');
                    $('#iconTray').append($newIcon);
                    $newIcon.show(500);
                }
            },
		auth       : {
			init: function(){
			    if($('#autogenerate').length > 0){
			    	$('#autogenerate').attr('checked',false);
			    }
			},
			valid: function(){
				if($("#autogenerate").attr("checked") == "checked")
				{
					$("#confirmation").hide();
				  	$("#confirmation-label label").hide();
				$.ajax({
				  url: "/auth/admin/generate-password",
				  type: "post",
				  cache: false,
				  success: function(data)
				  {
					$("#confirmation").val(data);
						$("#password").val(data);
				    $("#password").removeAttr("type");
				    $('#password').get(0).type = 'text';
				  }
					});
			    }
				else
				{
				$("#confirmation").val("");
					$("#confirmation").show();
					$("#confirmation-label label").show();
				$("#password").val("");
				    $("#password").removeAttr("type");
				    $('#password').get(0).type = 'password';
				}
			}
		},

            documentation       : {
                init : function() {
                    if ($('#viewport_documentation').length == 0) {
                        return;
                    }
                    $('#viewport_documentation').css({bottom : $('#footer').height()});
                    $('#viewport_documentation .editing').removeClass('editing');
                    $('#viewport_documentation .close').show();
                    bob.actions.document.documentation.updateHeight();
                    $.get(
                        '/documentation/index/index/inline_module/'
                      + bob.state.page.module + '/inline_controller/'
                      + bob.state.page.controller + '/inline_action/'
                      + bob.state.page.action, function(response) {
                          $('#btnDocumentation').show();
                          bob.state.page.documentation = response;
                          if (response.id_documentation == null) {
                              $('#viewport_documentation .label').html('No documentation available<div class="existing"></div>');
                              $('#viewport_documentation .content .textfield').html('There is no documentation available for this page.<br /><br />Click here and be the first to write one...<div class="existing"></div>');
                              bob.actions.document.documentation.updateHeight();
                              return;
                          }
                          $('#btnDocumentation').html('Documentation for ' + response.label);
                          $('#viewport_documentation .label').html('<div class="existing">'+response.label+'</div>');
                          var html = '<i><strong>last update:</strong><br />'+response.created_at+', '+response.acl_username+'</i><br /><br /><div class="existing">'+response.textile+'</div>';
                          $('#viewport_documentation .content .textfield').html(html)
                          .children('.existing').children('a').click(function(event) {
                              event.stopPropagation();
                          });
                      }
                    );
                },
                toggle : function(btnObj) {
                    btnObj.toggleClass('active');
                    if ($('#viewport_documentation').is(':visible')) {
                        $('#viewport_main').animate({width:'100%'}, 250);
                        $('#viewport_documentation').animate({width:'0%', opacity: 0}, 250, function() {
                            $(this).hide();
                            $('body').css('overflow-x','auto');
                            btnObj.removeClass('active');
                        });
                    } else {
                        $('#viewport_main').animate({width:'78%'}, 250);
                        $('#viewport_documentation').show().animate({width:'22%', opacity: 1}, 250, function() {
                            $('body').css('overflow-x','hidden');
                            btnObj.addClass('active');
                        });
                    }
                },
                updateHeight : function() {
                	$('#viewport_documentation').css({height : ($(window).height() - $('#footer').height() - $('#header').height())});
                    $('#viewport_documentation .content').css({height : $('#viewport_documentation').height()});
                    $('#viewport_documentation .content .textfield').css({height : $('#viewport_documentation .content').height() - $('#viewport_documentation .label').height() - 100});
                },
                save : function() {
                    if ($('#viewport_documentation .label input, #viewport_documentation .content .textfield textarea').length == 0) {
                        return;
                    }
                    var label = $('#viewport_documentation .label input, #viewport_documentation .label .existing');
                    label = (label.val() ? label.val() : label.text());
                    var documentation = $('#viewport_documentation .content .textfield textarea, #viewport_documentation .content .textfield .existing');
                    documentation = (documentation.val() ? documentation.val() : documentation.html());
                    if (!((bob.state.page.documentation.documentation == documentation && bob.state.page.documentation.label == label) || (documentation == '' && label == ''))) {
                        $.ajax({
                            url : '/documentation/index/save/inline_module/'
                                + bob.state.page.module + '/inline_controller/'
                                + bob.state.page.controller + '/inline_action/'
                                + bob.state.page.action + '?documentation='
                                + encodeURIComponent(documentation) + '&label='
                                + encodeURIComponent(label),
                            async : false
                        });
                    }
                    bob.actions.document.documentation.init();
                },
                edit : {
                    label : function() {
                        if ($('#viewport_documentation .label input').length > 0) {
                            return;
                        }
                        $('#viewport_documentation .close').hide();
                        $('#viewport_documentation .label').addClass('editing').html('<input type="text" class="form_input" value="'+$('#viewport_documentation .label .existing').html()+'" />');
                        $('#viewport_documentation .label input').focus();
                    },
                    content : function() {
                        if ($('#viewport_documentation .content .textfield textarea').length > 0) {
                            return;
                        }
                        $('#viewport_documentation .content .textfield').addClass('editing').html('<textarea class="form_input">'+bob.state.page.documentation.documentation+'</textarea>');
                        $('#viewport_documentation .content .textfield textarea').focus();
                    }
                }
            },            
            navigation          : {
                openSubmenu         : function(i) {
                    $("#navigation > .item:eq("+i+") .subMenu").show().parent().siblings().children('.subMenu').hide();
                    if ($("#navigation > .item:eq("+i+")").attr("href")) {
                        window.document.location = $("#navigation > .item:eq("+i+")").attr("href");
                    }
                    return false;
                },
                openSubmenuLink     : function(i) {
                    if ($("#navigation > .item .subMenu:visible .container > .item:eq("+i+")").attr("href")) {
                        window.document.location = $("#navigation > .item .subMenu:visible .container > .item:eq("+i+")").attr("href");
                    } else {
                        bob.actions.document.navigation.openSubmenu(i);
                    }
                    return false;
                },
                initiateGridRowLinking : function() {
                    $("table.grid.linked tbody tr").click(function() {
                        if ($(this).find("td:first-child a").length > 0 && document.getSelection().toString() == "" && $(this).find("input[type=text]:focus").length == 0) {
                            var obj = $(this).find("td:first-child a:first-child");
                            if (obj.hasClass('toggleRow')) {
                                bob.actions.document.general.toggleGridRow(obj);
                                return;
                            }
                            document.location = obj.attr("href");
                        }
                    });
                },
                initiateGridRowShortcuts : function() {
                    counter = 0;
                    $.each($("table.grid.linked tbody tr td:last-child"), function() {
                        counter++;
                        if (counter > 9) {
                            shortcut = String.fromCharCode(counter+55);
                        } else {
                            shortcut = counter;
                        }
                        $(this).append('<div class="keyHint">'+shortcut+'</div>');
                    });
                },
                toggleGridKeyHint : function(makeVisible) {
                    el = $("table.grid.linked tbody tr td:last-child .keyHint");
                    if (makeVisible) {
                        el.show();
                        bob.state.visibility.gridKeyHint = true;
                    } else {
                        el.hide();
                        bob.state.visibility.gridKeyHint = false;
                    }
                },
                findSaveButton : function(el) {
                    target = false;
                    if (el.length > 0) {
                        $.each(el, function() {
                            if (bob.statics.names.saveBtn[$(this).html()] || bob.statics.names.saveBtn[$(this).attr("value")]) {
                                target = $(this);
                                return false;
                            } else {
                                return true;
                            }
                        });
                    }
                    if (target) {
                        return target;
                    } else {
                        return $(false);
                    }
                },
                submitActiveForm : function(el) {
                    if (typeof customSubmitControl !== 'undefined') {
                        customSubmitControl();
                        return false;
                    }
                    if (el.parents("form").length > 0) {
                        // extJs support
                        if (el.parents("form").find("button[type=submit]:visible, button[type=button]:visible").length > 0) {
                            if (bob.actions.document.navigation.findSaveButton(el.parents("form").find("button[type=submit]:visible, button[type=button]:visible")).click().length == 0) {
                                if (bob.actions.document.navigation.findSaveButton(el.parents("#main").find("button[type=submit]:visible, button[type=button]:visible")).click().length == 0) {
                                    bob.actions.document.navigation.findSaveButton($("#main").find("input[type=submit]:visible")).click();
                                }
                            }
                        } else {
                            if ($("#main").find("button[type=submit]:visible, button[type=button]:visible").length > 0) {
                                if (bob.actions.document.navigation.findSaveButton($("#main").find("button[type=submit]:visible, button[type=button]:visible")).click().length == 0) {
                                    bob.actions.document.navigation.findSaveButton($("#main").find("input[type=submit]:visible")).click();
                                }
                            } else {
                                el.parents("form").trigger('submit');
                                el.parents("form").get(0).submit();
                            }
                        }
                    } else {
                        if (el.find("form:visible").length == 1) {
                            el.find("form:visible").trigger('submit');
                            el.find("form:visible").get(0).submit();
                        } else {
                            if ($("#main").find("button[type=submit]:visible, button[type=button]:visible").length > 0) {
                                bob.actions.document.navigation.findSaveButton($("#main").find("button[type=submit]:visible, button[type=button]:visible")).click();
                            }
                        }
                    }
                }
            }
        }
    }
}


$(document).ready(function(){
    $(document.body).focus();
    bob.services.workspace();
    bob.actions.document.navigation.initiateGridRowLinking();
    bob.actions.document.navigation.initiateGridRowShortcuts();
    document.onkeydown = bob.actions.registerKey;
    document.onkeyup = bob.actions.unregisterKey;
    $("#navigation > .item .subMenu").mouseleave(function() {
        $(this).hide();
    });
    $(".extrasBar .item.expandable").click(function(){
        $(this).toggleClass('active').siblings().removeClass('active');
        $(this).find("form").each(function(){
            this.reset();
        }).find("input[type=text]").removeClass('active');
    });
    $(".extrasBar .item.expandable .subMenu").click(function(event) {
        event.stopPropagation();
    });
    $(".extrasBar .item.expandable .action").click(function() {
        $(".item.expandable").removeClass("active");
    });
    $(".extrasBar .item.expandable .subMenu form").submit(function() {
        $(".extrasBar .item.active").removeClass("active");
        $.each($(this).find("input[type=text], textarea"), function() {
            if ($(this).val() == this.defaultValue) {
                $(this).val("");
            }
        });

        /*
         * Filter form has only hidden fields
         * is one set to 1 and isn't called active Flags
         */
        var myFlag = false;
        var isFlagForm = false;
        $.each($(this).find("input[type=hidden]"), function() {
            if($(this).attr('value')==1 && $(this).attr('name')!='activeFlags'){
                myFlag = true;
            }
            isFlagForm = true;

        });
        if(isFlagForm){
            return myFlag;
        }

        return true;
    });
    $('table.grid tbody tr td .editable_input').parent('td').css('padding', '0 3px');

    if ($('ul.message').length !== 0) {
        window.setTimeout(function() {
            $('ul.message').slideUp(500);
            bob.actions.document.general.pushToIconTray('information', "$('ul.message').slideDown(500); $(this).hide(500);");
        }, 8000);
    }

    bob.actions.document.general.interactivateInputs(".extrasBar .item .subMenu input[type=text], .extrasBar .item .subMenu textarea");
    bob.actions.document.general.interactivateSwitches(".extrasBar .item .subMenu.flags .flag");
    bob.actions.document.general.datefields();
    bob.actions.document.general.rangesliders();
    
    bob.actions.document.documentation.init();
    $(window).resize(function(){
        bob.actions.document.documentation.updateHeight();
    });
    $('#viewport_main').click(function() {
        if ($('#btnDocumentation').hasClass('active')) {
            bob.actions.document.documentation.save();
        }
    });
    
    $('#btnDocumentation').click(function() {
        bob.actions.document.documentation.toggle($(this));
    });
    
});