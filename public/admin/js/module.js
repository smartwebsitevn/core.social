/**
 * -------------------------------
 * Search module
 * Javascript for search module
 * -------------------------------
 *
 */

$(document).ready(function () {
    // Question
    $('body').on('click', '.btn-answer-remove', function(){
        $(this).parent().parent().remove();
    });
    $('body').on('click', '.btn-feedback-remove', function(){
        $(this).parent().remove();
    });
    // Macros AdvFrom on search remove
    $('.quick-search-choosen').on('click', '.item .close', function () {
        $(this).parent().remove();
        $('input[name="' + $(this).data('obj') + '"]').val('').change();
    });

    $('.quick-search-choosen').on('click', '.item-multiple .close', function () {
        $(this).parent().remove();
        var id = $(this).data('id');
        var old = $('input[name="' + $(this).data('obj') + '"]').val();

        if (old) {
            data = $.parseJSON(old);

            // Remove quotes from array
            for (var i in data) {
                if (data.hasOwnProperty(i)) {
                    data[i] = parseInt(data[i]);
                }
            }

            // Remove element
            if (data.indexOf(id) != -1) {
                data.splice(data.indexOf(id), 1);
                $('input[name="' + $(this).data('obj') + '"]').val(JSON.stringify(data)).change();
            }
        }
    });

});


function search_choose(obj, param) {
    var id = obj.data('value'),
        information = obj.data('information'),
        div = create('div', {className: 'item'}),                                   // Container
        name = createAppend('p', create('strong', {innerHTML: obj.text()})),      // Name
        close = create('i', {className: 'fa fa-close'}),
        a = create('a', {
            className: 'close',
            href: 'javascript:;',
            'dataset.obj': param,
        });
    a.appendChild(close);


    // Ảnh đại diện
    if (obj.data('thumb')) {
        div.appendChild(
            create(
                'img',
                {
                    src: obj.data('thumb')
                }
            )
        );
    }

    // Thêm tên
    div.appendChild(name);

    // Thông tin
    if (information != 'undefined') {
        div.appendChild(
            create(
                'p',
                {
                    innerHTML: information
                }
            )
        );
    }

    // Thêm nút Xóa
    div.appendChild(a);

    $('#quick-' + param + '-choosen').html(div);
    $('input[name="' + param + '"]').val(id).change();
    ;

    obj.remove();
    $('#quick-' + param + '-result').hide();
}


function search_multiple_choose(obj, param) {
    var id = obj.data('value'),
        information = obj.data('information'),
        div = create('div', {className: 'item-multiple'}),                          // Container
        name = createAppend('p', create('strong', {innerHTML: obj.text()})),      // Name
        close = create('i', {className: 'fa fa-close'}),                            // Close icon
        a = create('a', {                                                              // Close button
            className: 'close',
            href: 'javascript:;',
            'dataset.obj': param,
            'dataset.id': id
        }),
        exist = $('input[name="' + param + '"]').val();                                        // Exist values
    a.appendChild(close);

    // Ảnh đại diện
    if (obj.data('thumb')) {
        div.appendChild(
            create(
                'img',
                {
                    src: obj.data('thumb')
                }
            )
        );
    }

    // Thêm tên
    div.appendChild(name);

    // Thông tin
    if (information != 'undefined') {
        div.appendChild(
            create(
                'p',
                {
                    innerHTML: information
                }
            )
        );
    }

    // Thêm nút Xóa
    div.appendChild(a);


    $('#quick-' + param + '-choosen').append(div);

    if (exist) {
        data = $.parseJSON(exist);

        // Remove quotes from array
        for (var i in data) {
            if (data.hasOwnProperty(i)) {
                data[i] = parseInt(data[i]);
            }
        }

        if (data.indexOf(id) == -1) {
            data.push(id);
        }
    }
    else {
        data = new Array;
        data.push(id);
    }


    $('input[name="' + param + '"]').val(JSON.stringify(data)).change();
    ;
    obj.remove();
    $('#quick-' + param + '-result-multiple').hide();
}


/**
 * Base functions
 * @param  {[type]} ele  [description]
 * @param  {[type]} data [description]
 * @return {[type]}      [description]
 */
function create(ele, data) {
    var ele = document.createElement(ele);
    $.each(data, function (i, k) {
        if (i.indexOf('.') !== -1) {

            var tmp = i.split('.');
            if (tmp[0] != 'data')
                ele[tmp[0]][tmp[1]] = k;
            else
                ele.setAttribute('data-' + tmp[1], k);

        }
        else {

            ele[i] = k;
        }
    });
    //nfc.pr(data);    nfc.pr(ele);

    return ele;
}

function createAppend(ele, child) {
    var td = document.createElement(ele);
    if (child)
        td.appendChild(child);
    return td;
}
