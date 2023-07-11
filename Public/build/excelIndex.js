function ExportsEXCL(name) {
    this.downLoad = ({
        header = [],
        body = [],
        excelName = name,
        hasTitle = true,
    }) => {
        const styleCell = this.setBorderStyle();
        const _headers = header.map((v, i) => {
                let key = Object.keys(v);
                return Object.assign({}, {
                    v: `${v[key[0]]}<key>${key[0]}`,
                    position: String.fromCharCode(65 + i) + (hasTitle ? 1 : 0)
                });
            })
            .reduce(
                (prev, next) =>
                Object.assign({}, prev, {
                    [next.position]: {
                        v: next.v,
                        s: styleCell
                    }
                }), {}
            );
        const _body = body
            .map((v, i) =>
                header.map((k, j) => {
                    let key = Object.keys(k);
                    return Object.assign({}, {
                        v: v[key[0]],
                        position: String.fromCharCode(65 + j) + (i + (hasTitle ? 2 : 1))
                    });
                })
            )
            .reduce((prev, next) => prev.concat(next))
            .reduce(
                (prev, next) =>
                Object.assign({}, prev, {
                    [next.position]: {
                        v: next.v,
                        s: styleCell
                    }
                }), {}
            );
        const mergeThead = this.setMergeThead(_headers, hasTitle);
        const _thead = this.setTableThead(mergeThead);
        const output = Object.assign({}, _thead, _body);
        const outputPos = Object.keys(output);
        const ref = outputPos[0] + ':' + outputPos[outputPos.length - 1];
        const wb = {
            SheetNames: ['mySheet'],
            Sheets: {
                mySheet: Object.assign({}, output, {
                    '!ref': ref,

                })
            }
        };
        this.save(wb, `${excelName}.xlsx`);
    };
    this.setTableThead = wb => {
        for (let key in wb) {
            let i = wb[key].v.indexOf('<key>');
            if (wb[key].v.includes('<key>')) {
                wb[key].v = wb[key].v.substr(0, i);
            }
        }
        return wb;
    };
    this.setMergeThead = (wb, hasTitle) => {
        const borderAll = {
            top: {
                style: 'thin'
            },
            bottom: {
                style: 'thin'
            },
            left: {
                style: 'thin'
            },
            right: {
                style: 'thin'
            }
        };
        return wb;
    };

    this.setBorderStyle = () => {
        const borderAll = {
            top: {
                style: 'thin'
            },
            bottom: {
                style: 'thin'
            },
            left: {
                style: 'thin'
            },
            right: {
                style: 'thin'
            }
        };
        return {
            border: borderAll
        };
    };
    this.save = (wb, fileName) => {
        let wopts = {
            bookType: 'xlsx',
            bookSST: false,
            type: 'binary'
        };
        let xw = XLSX.write(wb, wopts);

        let obj = new Blob([this.s2ab(xw)], {
            type: ''
        });
        let elem = document.createElement('a');
        elem.download = fileName || '下载';
        elem.href = URL.createObjectURL(obj);
        elem.click();
        setTimeout(function() {
            URL.revokeObjectURL(obj);
        }, 100);
    };

    this.s2ab = s => {
        if (typeof ArrayBuffer !== 'undefined') {
            var buf = new ArrayBuffer(s.length);
            var view = new Uint8Array(buf);
            for (var i = 0; i != s.length; ++i) view[i] = s.charCodeAt(i) & 0xff;
            return buf;
        } else {
            var buf = new Array(s.length);
            for (var i = 0; i != s.length; ++i) buf[i] = s.charCodeAt(i) & 0xff;
            return buf;
        }
    };

    // 根据val查询Object key
    this.findKey = (val, obj) => {
        return Object.keys(obj).find(v => obj[v] === val);
    };
}