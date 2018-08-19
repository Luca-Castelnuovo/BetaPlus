/* Imgur Upload Script */
(function(root, factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        define([], factory);
    } else if (typeof exports === 'object') {
        module.exports = factory();
    } else {
        root.Imgur = factory();
    }
}(this, function() {
    "use strict";
    var Imgur = function(options) {
        if (!this || !(this instanceof Imgur)) {
            return new Imgur(options);
        }

        if (!options) {
            options = {};
        }

        if (!options.clientid) {
            throw 'Provide a valid Client Id here: https://api.imgur.com/';
        }

        this.clientid = options.clientid;
        this.endpoint = 'https://api.imgur.com/3/image';
        this.callback = options.callback || undefined;
        this.dropzone = document.querySelectorAll('.dropzone');
        this.info = document.querySelectorAll('.info');

        this.run();
    };

    Imgur.prototype = {
        createEls: function(name, props, text) {
            var el = document.createElement(name),
                p;
            for (p in props) {
                if (props.hasOwnProperty(p)) {
                    el[p] = props[p];
                }
            }
            if (text) {
                el.appendChild(document.createTextNode(text));
            }
            return el;
        },
        insertAfter: function(referenceNode, newNode) {
            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        },
        post: function(path, data, callback) {
            var xhttp = new XMLHttpRequest();

            xhttp.open('POST', path, true);
            xhttp.setRequestHeader('Authorization', 'Client-ID ' + this.clientid);
            xhttp.onreadystatechange = function() {
                if (this.readyState === 4) {
                    if (this.status >= 200 && this.status < 300) {
                        var response = '';
                        try {
                            response = JSON.parse(this.responseText);
                        } catch (err) {
                            response = this.responseText;
                        }
                        callback.call(window, response);
                    } else {
                        throw new Error(this.status + " - " + this.statusText);
                    }
                }
            };
            xhttp.send(data);
            xhttp = null;
        },
        createDragZone: function() {
            var p1, p2, input;

            p1 = this.createEls('p', {
                className: 'top flow-text'
            }, 'Drop Image Here');
            p2 = this.createEls('p', {
                className: 'flow-text'
            }, 'Or click here to select image');
            input = this.createEls('input', {
                type: 'file',
                className: 'input',
                accept: 'image/*'
            });

            Array.prototype.forEach.call(this.info, function(zone) {
                zone.appendChild(p1);
                zone.appendChild(p2);
            }.bind(this));
            Array.prototype.forEach.call(this.dropzone, function(zone) {
                zone.appendChild(input);
                this.status(zone);
                this.upload(zone);
            }.bind(this));
        },
        status: function(el) {},
        matchFiles: function(file, zone) {
            var status = zone.nextSibling;

            if (file.type.match(/image/) && file.type !== 'image/svg+xml') {
                var loader = document.querySelector('.preloader-wrapper');
                document.querySelector('.info').removeChild(document.querySelector('.info p'));
                document.querySelector('.info').removeChild(document.querySelector('.info p'));
                loader.classList.remove('hide')
                loader.classList.add('active');

                var fd = new FormData();
                fd.append('image', file);

                this.post(this.endpoint, fd, function(data) {
                    typeof this.callback === 'function' && this.callback.call(this, data);
                }.bind(this));
            } else {
                loader.classList.add('hide');
                loader.classList.remove('active');
                document.querySelector('.center-align h1').innerHTML = 'Invalid archive';
            }
        },
        upload: function(zone) {
            var events = ['dragenter', 'dragleave', 'dragover', 'drop'],
                file, target, i, len;

            zone.addEventListener('change', function(e) {
                if (e.target && e.target.nodeName === 'INPUT' && e.target.type === 'file') {
                    target = e.target.files;

                    for (i = 0, len = target.length; i < len; i += 1) {
                        file = target[i];
                        this.matchFiles(file, zone);
                    }
                }
            }.bind(this), false);

            events.map(function(event) {
                zone.addEventListener(event, function(e) {
                    if (e.target && e.target.nodeName === 'INPUT' && e.target.type === 'file') {
                        if (event === 'dragleave' || event === 'drop') {
                            e.target.parentNode.classList.remove('dropzone-dragging');
                        } else {
                            e.target.parentNode.classList.add('dropzone-dragging');
                        }
                    }
                }, false);
            });
        },
        run: function() {
            this.createDragZone();
        }
    };

    return Imgur;
}));

var feedback = function(res) {
    if (res.success === true) {
        var get_link = res.data.link.replace(/^http:\/\//i, 'https://');

        var infodiv = document.querySelector('.info');
        while (infodiv.firstChild) {
            infodiv.removeChild(infodiv.firstChild);
        }
        infodiv.innerHTML += '<h3 class="center-align">Success</h3>';

        var CSRFtoken = document.querySelector('#CSRFtoken');

        $.ajax({
            type: "POST",
            url: '/general/upload.php',
            json: {CSRFtoken: CSRFtoken.value, type:'leerling_profile_picture', url:get_link},
            success: function (response) {
                if(response['status']) {
                    console.log('success', response['url']);
                    // location.replace(response['url']);
                } else {
                    console.log('error', response['url']);
                    // location.replace(response['url']);
                }
            }
        });
    }
};

new Imgur({
    clientid: 'b2c72661027878c',
    callback: feedback
});
