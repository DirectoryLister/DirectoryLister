$(document).ready(function() {
    
    //Grabbing the url bits from the location, messy, but works
	try {
		var currentdir = window.location.href.split("=")[1].split("&by")[0]; //if directory doesn't have = everything fails
	}
	catch {
		var currentdir = '';
	}
	var basedir = window.location.href.split("/")[3];
	var server = window.location.href.split("/")[2];
	var file = "http://" + server + "/" + basedir + "/" + currentdir + "/" + ".desc";
	
	//Read the contents of .desc file if it exists
	var result = null;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", file, false);
	xmlhttp.send();
	if (xmlhttp.status==200) {
		result = xmlhttp.responseText;
		//Replace the header description with the one from the .desc file
		document.getElementById('description').innerHTML="<hr>" + result + "<hr>";
	}

    // Get page-content original position
    var contentTop = $('#page-content').offset().top;

    // Show/hide top link on page load
    showHideTopLink(contentTop);

    // Show/hide top link on scroll
    $(window).scroll(function() {
        showHideTopLink(contentTop);
    });

    // Scroll page on click action
    $('#page-top-link').click(function() {
        $('html, body').animate({ scrollTop: 0 }, 'fast');
        return false;
    });

    // Hash button on click action
    $('.file-info-button').click(function(event) {

        // Get the file name and path
        var name = $(this).closest('li').attr('data-name');
        var path = $(this).closest('li').attr('data-href');

        // Set modal title value
        $('#file-info-modal .modal-title').text(name);

        $('#file-info .md5-hash').text('Loading...');
        $('#file-info .sha1-hash').text('Loading...');
        $('#file-info .filesize').text('Loading...');
		$('#file-info .file-downloads').text('Loading...');

        $.ajax({
            url:     '?hash=' + path,
            type:    'get',
            success: function(data) {

                // Parse the JSON data
                var obj = jQuery.parseJSON(data);

                // Set modal pop-up hash values
                $('#file-info .md5-hash').text(obj.md5);
                $('#file-info .sha1-hash').text(obj.sha1);
                $('#file-info .filesize').text(obj.size);
				$('#file-info .file-downloads').text(obj.downloads); //Download Count

            }
        });

        // Show the modal
        $('#file-info-modal').modal('show');

        // Prevent default link action
        event.preventDefault();

    });
	
	//Light/Dark Toggle logic is here
	var checkbox = document.querySelector('#switch');
		
	//Cookie
	//Cookie for our switch is here and we will use it to set our theme
	let cookie = readCookie('checkbox');
	if (cookie == "true") {
		checkbox.setAttribute('checked', '');
		console.log("You want dark theme, you get Dark theme :)");
	}
	else {
		checkbox.removeAttribute('checked');
		console.log("Dark theme is nice and your eyes don't hurt when looking at it at night :)")
	}
	if(checkbox.checked) {
            document.documentElement.setAttribute('data-theme', 'dark')
        } else {
            document.documentElement.setAttribute('data-theme', 'light')
        }
    checkbox.addEventListener('change', function() {
        if(this.checked) {
			writeCookie('checkbox', true, 3);
            trans()
            document.documentElement.setAttribute('data-theme', 'dark')
        } else {
			writeCookie('checkbox', false, 3);
            trans()
            document.documentElement.setAttribute('data-theme', 'light')
        }
    })

        let trans = () => {
            document.documentElement.classList.add('transition');
            window.setTimeout(() => {
                document.documentElement.classList.remove('transition')
            }, 1000)
        }

});

function showHideTopLink(elTop) {
    if($('#page-navbar').offset().top + $('#page-navbar').height() >= elTop) {
        $('#page-top-nav').show();
    } else {
        $('#page-top-nav').hide();
    }
}
//Cookie stuff (Thanks SO: https://stackoverflow.com/a/2257895/3368585 )
function writeCookie(name,value,days) {
    var date, expires;
    if (days) {
        date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires=" + date.toGMTString();
            }else{
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}
function readCookie(name) {
    var i, c, ca, nameEQ = name + "=";
    ca = document.cookie.split(';');
    for(i=0;i < ca.length;i++) {
        c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) == 0) {
            return c.substring(nameEQ.length,c.length);
        }
    }
    return '';
}