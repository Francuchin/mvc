/*--- Request ---*/
'use strict'
let Request = OBJ => {
  return new Promise((resolve, reject) => {
    let xhr = new XMLHttpRequest()
    xhr.open(OBJ.method || "GET", OBJ.url)
    if (OBJ.headers) Object.keys(OBJ.headers).forEach( key => xhr.setRequestHeader(key, OBJ.headers[key]) )
    xhr.onload = () => {
      if (xhr.status >= 200 && xhr.status < 300) resolve(xhr.response)
      else reject(xhr.statusText)
    }
    xhr.onerror = () => reject(xhr.statusText)
    xhr.send(OBJ.body)
  })
}
