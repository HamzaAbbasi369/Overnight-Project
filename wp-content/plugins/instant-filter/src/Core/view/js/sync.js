function runStep(step) {
    var timeout = 5;
    var unrecognizedError = 'Unrecoverable Error. Please contact to support';
    if (typeof ongSyncAjaxUrl == 'undefined') {
        document.getElementById("sync-rows").innerHTML = unrecognizedError;
        return;
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status != 200) {
            setTimeout(runStep, timeout * 3, step); //try the same step again
            //document.getElementById("sync-rows").innerHTML = unrecognizedError;
            return;
        }
        if (this.readyState == 4 && this.status == 200) {
            try {
                var data = JSON.parse(this.response);
            } catch (e) {
                setTimeout(runStep, timeout * 3, step); //try the same step again
                //document.getElementById("sync-rows").innerHTML = unrecognizedError;
                return;
            }

            document.getElementById("prepare-sync").style.display = 'none';

            if (!data.finish) {
                for (var i in data.html) {
                    if (!document.getElementById("sync-rows-" + i)) {
                        document.getElementById("sync-rows").innerHTML += '' +
                            '<li><b>' + data.html[i]['name'] + ':</b>' +
                            '<ul id="' + "sync-rows-" + i + '"></ul>' +
                            '</li>' +
                            '';
                    }
                    delete data.html[i]['name'];
                    for (var j in data.html[i]) {
                        if (!document.getElementById("sync-rows-" + i + '-' + j)) {
                            var rowTitle = j;
                            if (rowTitle) {
                                rowTitle += ':';
                            }
                            document.getElementById("sync-rows").innerHTML += '' +
                                '<li>' +
                                '<ul id="' + "sync-rows-" + i + '-' + j + '"></ul>' +
                                '</li>' +
                                '';
                        }
                        if (data.html[i][j]) {
                            document.getElementById("sync-rows-" + i + '-' + j).innerHTML = data.html[i][j];
                        }
                    }
                }
                setTimeout(runStep, timeout, ++step);
            } else {
                document.getElementById("sync-message-block").innerHTML = data.html;
            }
        }
    };

    var url = '';
    if (ongSyncAjaxUrl.search(/\?/) == -1) {
        url = ongSyncAjaxUrl + '?step=' + step;
    } else {
        url = ongSyncAjaxUrl + '&step=' + step;
    }
    url += '&isAjax=1';

    xhttp.open("POST", url);
    xhttp.send();
}

runStep(1);