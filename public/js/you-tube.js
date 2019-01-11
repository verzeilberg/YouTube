$(document).ready(function () {
    //Ajax function to add youTube video
    $('button#saveYouTubeVideo').on("click", function () {
        var youTubeLink = $('input[name=youtubevideolink]').val();
        var blogId = $('input[name=blogId]').val();
        if (youTubeLink != '') {
            $.ajax({
                type: 'POST',
                data: {
                    blogId: blogId,
                    youTubeLink: youTubeLink
                },
                url: "/blogajaxbeheer/addYouTubeVideo",
                async: true,
                success: function (data) {
                    if (data.succes === true) {
                        //Set default image and check if image is present
                        var thumbnail = '/img/novideofound.jpg';
                        if(data.YouTubeData.imageurl != '') {
                            thumbnail = data.YouTubeData.imageurl;
                        }

                        var html = '';
                        html = '<div id="youTubeFrame' + data.YouTubeData.youTubeId + '" class="col-md-4 mb-2 tile">';
                        html += '<img class="img-thumbnail" src="' + thumbnail + '" alt="' + data.YouTubeData.title + '" />';
                        html += '<div>';
                        html += '<span data-youtubeid="' + data.YouTubeData.youTubeId + '" class="removeYouTubeVideo btn btn-secondary btn-sm">';
                        html += '<i class="fas fa-trash-alt"></i>';
                        html += '</span>';
                        html += '&nbsp:' + data.YouTubeData.title.substring(0, 25) + '&hellip;';
                        html += '</div>';
                        html += '</div>';
                        $(html).hide().appendTo('div#youTubeVideosFrame').fadeIn(300);
                        $('input[name=youtubevideolink]').val('');
                    } else {
                        alert(data.errorMessage);
                    }
                }
            });
        }
    });

    //Ajax function to remove youTube video
    $(document).on("click", "span.removeYouTubeVideo", function () {
        var youTubeId = $(this).data('youtubeid');
        $.ajax({
            type: 'POST',
            data: {
                youTubeId: youTubeId
            },
            url: "/youtube/removeYouTubeVideo",
            async: true,
            success: function (data) {
                if (data.success === true) {
                    $('#youTubeFrame' + youTubeId).fadeOut(300, function () {
                        $('#youTubeFrame' + youTubeId).remove();
                    })
                } else {
                    alert(data.errorMessage);
                }
            }
        });

    });

    //Click function to show youTube video in pop-up
    $(document).on("click", "img.you-tube-pop-up", function () {
        var youTubeVideoId = $(this).data('youtubevideoid');

        $.ajax({
            type: 'POST',
            data: {
                youTubeVideoId: youTubeVideoId
            },
            url: "/youtube/getYouTubeVideo",
            async: true,
            success: function (data) {
                if (data.success === true) {
                    $('#youTubePopUpLabel').html(data.youTubeData.title);
                    $('iframe#youTubePopUpIframe').attr('src', 'https://www.youtube.com/embed/' + data.youTubeData.youTubeId);
                    $('#youTubeDescription').html(data.youTubeData.description);
                    $('#youTubeDuration').html(data.youTubeData.duration);
                    $('#youTubePopUp').modal();
                } else {
                    alert(data.errorMessage);
                }
            }
        });
    });
    //Empty modal input on close
    $('#youTubePopUp').on('hidden.bs.modal', function () {
        $('#youTubePopUpLabel').html('');
        $('iframe#youTubePopUpIframe').attr('src', '');
    })

});