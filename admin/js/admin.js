(function($){
    $(document).ready(function(){
        $( ".rx-schedule-time" ).datetimepicker({
            datepicker:false,
            format:'H:i',
            scrollTime:true,
            onSelect: function(dateText, inst) {
                $(this).attr('value', dateText);
            }
        });
        $( ".rx-schedule-date" ).datepicker({
            minDate: "new Date()",
            onSelect: function(dateText, inst) {
                $(this).attr('value', dateText);
            }
        });

        $("#rex-current-time-facebook").click(function(e){
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes();
            $('#schedule-time-facebook').val(time);
        });

        $("#rex-current-time-twitter").click(function(e){
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes();
            $('#schedule-time-twitter').val(time);
        });

        $("#rex-current-time-tumblr").click(function(e){
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes();
            $('#schedule-time-tumblr').val(time);
        });

        $("#rex-current-time-pinterest").click(function(e){
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes();
            $('#schedule-time-pinterest').val(time);
        });

        $("#rex-current-time-linkedin").click(function(e){
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes();
            $('#schedule-time-linkedin').val(time);
        });

        $("#rex-current-time-reddit").click(function(e){
            var dt = new Date();
            var time = dt.getHours() + ":" + dt.getMinutes();
            $('#schedule-time-reddit').val(time);
        });

        $("#rex-current-date-facebook").click(function(e){
          var date=new Date
          var months= ["January","February","March","April","May","June","July","August","September","October","November","December"];
          var val=months[date.getMonth()]+" "+date.getDate()+", "+date.getFullYear();
          $('#schedule-date-facebook').val(val);
        });

        $("#rex-current-date-twitter").click(function(e){
          var date=new Date
          var months= ["January","February","March","April","May","June","July","August","September","October","November","December"];
          var val=months[date.getMonth()]+" "+date.getDate()+", "+date.getFullYear();
          $('#schedule-date-twitter').val(val);
        });

        $("#rex-current-date-tumblr").click(function(e){
          var date=new Date
          var months= ["January","February","March","April","May","June","July","August","September","October","November","December"];
          var val=months[date.getMonth()]+" "+date.getDate()+", "+date.getFullYear();
          $('#schedule-date-tumblr').val(val);
        });

        $("#rex-current-date-pinterest").click(function(e){
          var date=new Date
          var months= ["January","February","March","April","May","June","July","August","September","October","November","December"];
          var val=months[date.getMonth()]+" "+date.getDate()+", "+date.getFullYear();
          $('#schedule-date-pinterest').val(val);
        });

        $("#rex-current-date-linkedin").click(function(e){
          var date=new Date
          var months= ["January","February","March","April","May","June","July","August","September","October","November","December"];
          var val=months[date.getMonth()]+" "+date.getDate()+", "+date.getFullYear();
          $('#schedule-date-linkedin').val(val);
        });

        $("#rex-current-date-reddit").click(function(e){
          var date=new Date
          var months= ["January","February","March","April","May","June","July","August","September","October","November","December"];
          var val=months[date.getMonth()]+" "+date.getDate()+", "+date.getFullYear();
          $('#schedule-date-reddit').val(val);
        });

        $(".global-icon-design").click(function(e){
            $(this).toggleClass("sb_blue");
            e.preventDefault();
        });
    });
})(jQuery);

(function() {
    jQuery(function() {
        var toggle;
        return toggle = new Toggle('.toggle');
    });

    this.Toggle = (function() {
        Toggle.prototype.el = null;

        Toggle.prototype.tabs = null;

        Toggle.prototype.panels = null;

        function Toggle(toggleClass) {
            this.el = jQuery(toggleClass);
            this.tabs = this.el.find(".tab");
            this.panels = this.el.find(".panel");
            this.bind();
        }

        Toggle.prototype.show = function(index) {
            var activePanel, activeTab;
            this.tabs.removeClass('active');
            activeTab = this.tabs.get(index);
            $(activeTab).addClass('active');
            this.panels.hide();
            activePanel = this.panels.get(index);
            return $(activePanel).show();
        };

        Toggle.prototype.bind = function() {
            var _this = this;
            return this.tabs.unbind('click').bind('click', function(e) {
                return _this.show($(e.currentTarget).index());
            });
        };

        return Toggle;

    })();

}).call(this);


jQuery(function($){
    /*
     * Select/Upload image(s) event
     */
    $('body').on('click', '.rx_sb_upload_image_button', function(e){

        e.preventDefault();

        var button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                library : {
                    // uncomment the next line if you want to attach image to the current post
                    // uploadedTo : wp.media.view.settings.post.id,
                    type : 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function() { // it also has "open" and "close" events
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:80%;display:block;" />').next().val(attachment.id).next().show();

            })
                .open();
    });

    /*
     * Remove image event
     */
    $('body').on('click', '.rx_sb_remove_image_button', function(){
        $(this).hide();
        $(this).parent('.featured-left').find('.rx_sb_upload_image_button').addClass('button');
        $(this).parent('.featured-left').find('.rx_sb_upload_image_button').html('UPLOAD IMAGE');
        return false;
    });

    $(document).on("click","button.share-btn.share-now-btn",function(e) {
        e.preventDefault();
        var ajaxurl = rx_obj.ajaxurl;
        $(this).attr('disabled' , true);
        $('#rex-fa-spin-instant').show();
        var postid = $("#post_ID").val();
        var data = [];
        var network_data = rx_obj.network_data;
        $.each(network_data, function (i, networks) {
            var id;
            var caption;
            $.each(networks, function (key, val) {
                if (key == 'id') {
                  if ($('#instant-share-profile-'+val).is(":checked"))
                  {
                    id = val;
                  }
                }
                if (key == 'network') {
                  caption = $('#post-caption-'+val).val();
                }
            });

            if (id) {
              data.push(
                {
                  id:id,
                  caption:caption
                }
              );
            }

        });

        jQuery.ajax({

            type:    "POST",
            url:     ajaxurl,
            data: {
                action: "rx_sb_post",
                data:data,
                postid: postid,
            },

            success: function( response ){
              $('#rex-fa-spin-instant').hide();
              if (response.success == false) {
                   $('button.share-btn.share-now-btn').attr('disabled' , false);
                   $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
                   $("#rx-sb-share-status .rx-sb-meta-modal p").html('<span>Warning:</span> Something wrong or the post is not published yet');
                   $("#rx-sb-share-status").addClass('error').show();

               }
               else {
                   $('button.share-btn.share-now-btn').attr('disabled' , false);
                   $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
                   $("#rx-sb-share-status .rx-sb-meta-modal p").html('<span>Success:</span> Successfully posted');
                   $("#rx-sb-share-status").addClass('success').show();
               }
            },

            error: function (response) {
               $('#rex-fa-spin-instant').hide();
               $('button.share-btn.share-now-btn').attr('disabled' , false);
               $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
               $("#rx-sb-share-status .rx-sb-meta-modal p").html('<span>Success:</span> Successfully posted');
               $("#rx-sb-share-status").addClass('success').show();
            }
        });
    });


    //----open graph data----
    $(document).on("click","button.og-submitted-data",function(e) {
        e.preventDefault();
        $('#rex-og-data-spin').show();
        var ajaxurl = rx_obj.ajaxurl;
        var title = $('#og-title').val();
        var description = $('#og-description').val();
        var postid = $("#post_ID").val();

        jQuery.ajax({

            type:    "POST",
            url:     ajaxurl,
            data: {
                action: "rx_sb_og_data",
                title: title,
                description: description,
                postid: postid,
            },
            success: function( response ){
                console.log(response);
                $('#rex-og-data-spin').hide();
                if(response.success){
                    $('button.og-submitted-data').attr('disabled' , false);
                    $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
                    $("#rx-sb-share-status .rx-sb-meta-modal p").html('<span>Success:</span> Successfully Saved');
                    $("#rx-sb-share-status").addClass('success').show();

                    $('#fb-preview-title').text(response.data.title);
                    $('#fb-preview-description').text(response.data.description);

                    $('#tw-preview-title').text(response.data.title);
                    $('#tw-preview-description').text(response.data.description);

                    $('#tm-preview-title').text(response.data.title);
                    $('#tm-preview-description').text(response.data.description);
                }else{
                    $('button.og-submitted-data').attr('disabled' , false);
                    $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
                    $("#rx-sb-share-status .rx-sb-meta-modal p").html('<span>Warning:</span> Something wrong or the post is not published yet');
                    $("#rx-sb-share-status").addClass('error').show();
                }
            },

        });
    });


    //----------schedule edit modal----------
    // $(".edit-schedule").on("click", function(e){
    $(document).on("click",".edit-schedule",function(e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var caption = $(this).attr("data-caption");
        var schedule = $(this).attr("data-schedule");

        var date = $(this).attr("data-date");
        var time = $(this).attr("data-time");

        $('.edit-submit').attr('data-id', id);
        $("#caption-input").val(caption);
        $("#schedule").val(schedule);
        $("#edit-modal-schedule-date").val(date);
        $("#edit-modal-schedule-time").val(time);

        $("#rx-sb-meta-schedule-edit-modal").show();
        $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
    });

    $(".edit-schedule").on("click", function(e){
        e.preventDefault();

        var id = $(this).attr("data-id");
        var caption = $(this).attr("data-caption");
        var schedule = $(this).attr("data-schedule");

        var date = $(this).attr("data-date");
        var time = $(this).attr("data-time");

        $('.edit-submit').attr('data-id', id);
        $("#caption-input").val(caption);
        $("#schedule").val(schedule);
        $("#edit-modal-schedule-date").val(date);
        $("#edit-modal-schedule-time").val(time);
        $("#rx-sb-meta-schedule-edit-modal").show();
        $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
    });

    $(".edit-submit").on("click", function(e){
        e.preventDefault();
        $('.edit-submit').attr('disabled' , true);
        $('#edit-submit-success').hide();
        $('#edit-submit-fail').hide();
        var id = $(this).attr("data-id");

        var date = $('#edit-modal-schedule-date').val();
        var time = $('#edit-modal-schedule-time').val();
        if (!date) {
          date = '';
        }
        if (!time) {
          time = '';
        }
        var caption = encodeURIComponent($('#caption-input').val());
        var schedule = $('#schedule').find(':selected').val();
        var req = window.rx_obj.api_url + '/editSchedule?id='+id+'&schedule='+schedule+'&caption='+caption+'&date='+date+'&time='+time+'';
        $.ajax({
            url: req,
            type: 'POST',
            success: function(data) {
              $('.edit-submit').attr('disabled' , false);
              if (data.success == false) {
                $('#edit-submit-fail').text(data.message);
                $('#edit-submit-fail').show();
              }
              else {
                $('#edit-submit-success').text(data.message+' Reloading the page...');
                $('#edit-submit-success').show();
                setTimeout(function(){// wait for 5 secs(2)
                     location.reload(); // then reload the page.(3)
                }, 1000);
              }
            },
            error: function(data) {
                $('.edit-submit').attr('disabled' , false);
                $('#edit-submit-fail').text('Failed to request');
                $('#edit-submit-fail').show();
            }
        });
    });
    //----------end schedule edit modal----------


    $("body, #rx-sb-share-status .cross, .rx-sb-meta-schedule-edit-modal .close").on("click",function() {
        $("#rx_sb_post_meta_box").removeClass("sb-meta-modal-overlay");
        $("#rx-sb-share-status").hide();
        $(".rx-sb-meta-schedule-edit-modal").hide();
    });

    $("#rx-sb-share-status, .rx-sb-meta-schedule-edit-modal, .edit-schedule, .facebook-preview, .twitter-preview, .tumblr-preview").on("click",function(e) {
        e.stopPropagation();
    });

  $(document).on("click","button.share-btn.schedule-now-btn",function(e) {
      e.preventDefault();
      var ajaxurl = rx_obj.ajaxurl;
      $(this).attr('disabled' , true);
      $('#rex-fa-spin-schedule').show();
      var postid = $("#post_ID").val();
      var data = [];
      var network_data = rx_obj.network_data;
      $.each(network_data, function (i, networks) {
          var id;
          var caption;
          var schedule_type;
          var schedule_date_time;

          $.each(networks, function (key, val) {
              if (key == 'id') {
                if ($('#instant-share-profile-'+val).is(":checked"))
                {
                  id = val;
                }
              }
              if (key == 'network') {
                caption = $('#post-caption-'+val).val();
                schedule_type = $('#rex-schedule-time-'+val).find(':selected').val();
                schedule_date = $('#schedule-date-'+val).val();
                schedule_time = $('#schedule-time-'+val).val();
                if (!schedule_date && !schedule_time) {
                  schedule_date_time = "none";
                }
                else {
                  schedule_date_time = schedule_date + ' ' + schedule_time;
                }
              }
          });

          if (id) {
            data.push(
              {
                id:id,
                caption:caption,
                schedule_type:schedule_type,
                schedule_date_time:schedule_date_time
              }
            );
          }

      });

      jQuery.ajax({

          type:    "POST",
          url:     ajaxurl,
          data: {
              action: "rx_sb_schedule",
              postid: postid,
              data: data,
          },

          success: function( response ){
              $('#rex-fa-spin-schedule').hide();
              if (response.success == false) {
                  $('button.share-btn.schedule-now-btn').attr('disabled' , false);
                  $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
                  $("#rx-sb-share-status .rx-sb-meta-modal p").html(response.data);
                  $("#rx-sb-share-status").addClass('error').show();
              }
              else {
                  $('button.share-btn.schedule-now-btn').attr('disabled' , false);
                  $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
                  $("#rx-sb-share-status .rx-sb-meta-modal p").html('<span>Success:</span> Successfully posted');
                  $("#rx-sb-share-status").addClass('success').show();
                  $.each(response.data, function(key,valueObj){
                      var network = key.split('&');
                      $('#post-meta-schedule-'+network[0]).append(valueObj);
                  });

              }
          },

          error: function (response) {
              $('#rex-fa-spin-schedule').hide();
              $('#rx-sb-schedule').attr('disabled' , false);
              $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
              $("#rx-sb-share-status .rx-sb-meta-modal p").html('<span>Error:</span> Failed to run the process');
              $("#rx-sb-share-status").addClass('error').show();
          }
      });

  });

  $(document).on("click",".delete-schedule",function(e) {
    e.preventDefault();
    if (confirm('Are you sure')) {
      $('.delete-schedule').attr('disabled' , true);
      $(this).parent().parent().remove();
      var schedile_id = $(this).attr("data-id");
      var req = window.rx_obj.api_url + '/ScheduleData/'+schedile_id;
      $.ajax({
          url: req,
          type: 'DELETE',
          success: function(data) {
              $('.delete-schedule').attr('disabled' , false);
              $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
              $("#rx-sb-share-status .rx-sb-meta-modal p").html('<span>Success:</span> Successfully removed');
              $("#rx-sb-share-status").addClass('success').show();
          },
          error: function(data) {
              $('.delete-schedule').attr('disabled' , false);
              $("#rx_sb_post_meta_box").addClass("sb-meta-modal-overlay");
              $("#rx-sb-share-status .rx-sb-meta-modal p").html('<span>Success:</span> Failed to removed');
              $("#rx-sb-share-status").addClass('error').show();
          }
      });
    }
  });



    $(document).ready(function ($) {

        //----rx-sb-meta-tab js-------
        $('.rx-sb-meta-tab-content').hide();
        $('.rx-sb-meta-tab-content:first-child').show();
        $('.rx-sb-meta-tab .rx-sb-meta-tabs li.nav-item:first-child').addClass('active');
        $('.rx-sb-meta-tab .rx-sb-meta-tabs li.nav-item').click(function (event) {
            event.preventDefault();
            $('.rx-sb-meta-tabs li.nav-item').removeClass('active');
            $('#schedule-share').removeClass('active');
            $(this).addClass('active');
            $('.rx-sb-meta-tab-content').hide();

            var selectTab = $(this).find('a').attr("href");
            $(selectTab).show();
        });

        //----instant-share-child-tab js-------
        $('.instant-share-child-tab-content').hide();
        $('.instant-share-child-tab-content:first-child').show();
        $('.rx-sb-meta-tab .instant-share-child-tabs li:first-child').addClass('active');
        $('.rx-sb-meta-tab .instant-share-child-tabs li').click(function (event) {
            event.preventDefault();
            $('.instant-share-child-tabs li').removeClass('active');
            $(this).addClass('active');
            $('.instant-share-child-tab-content').hide();

            var instantShareChildTab = $(this).find('a').attr("href");
            $(instantShareChildTab).show();
        });

        //----meta-reschedule-child-tab js-------
        $('.meta-reschedule-child-tab-content').hide();
        $('.meta-reschedule-child-tab-content:first-child').show();
        $('.rx-sb-meta-tab .meta-reschedule-child-tabs li:first-child').addClass('active');
        $('.rx-sb-meta-tab .meta-reschedule-child-tabs li').click(function (event) {
            event.preventDefault();
            $('.meta-reschedule-child-tabs li').removeClass('active');
            $(this).addClass('active');
            $('.meta-reschedule-child-tab-content').hide();

            var rescheduleChildTab = $(this).find('a').attr("href");
            $(rescheduleChildTab).show();
        });


        //---------schedule option show hide----------
        $('.schedule-now-btn, .rx-sb-share-schedule-option').hide();

        $('#schedule-share').on("click", function (event) {
            event.preventDefault();
            $('#rx-sb-instant-share').show();
            $('#rx-sb-reschedule').hide();

            $('.schedule-now-btn, .rx-sb-share-schedule-option').show();
            $('.share-now-btn').hide();

            $('.rx-sb-meta-tabs li.nav-item').removeClass('active');
            $(this).addClass('active');

        });

        $('#instant-share, #reschedule').on("click", function () {
            $('.schedule-now-btn, .rx-sb-share-schedule-option').hide();
            $('.share-now-btn').show();
        });

    });

});
