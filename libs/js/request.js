'use strict'
/**
* @define Alta peticion hecha por Yo we, Jean xD nos vimos
*
* @function Request
* @param  Object => {url:requerido, method:opcional, body:{opcional}, headers:{opcional}}
* @return response
* @example Request({url:'http://loquesea.wea'}).then(d=>console.log(d)).catch(e=>console.log(e))
*
**/
let Request = OBJ => { // aca arrancamo
  return new Promise((resolve, reject) => { // tremenda promesa asi que se llama asi: Request({OBJ}).then(d=>{}).catch(e=>{})
    let xhr = new XMLHttpRequest() // se crea el super mega xmlhttprequest
    xhr.open(OBJ.method || "GET", OBJ.url) // se setea la url y el metodo por defecto es GET
    if (OBJ.headers) Object.keys(OBJ.headers).forEach( key => xhr.setRequestHeader(key, OBJ.headers[key]) ) // se agregan headers si corresponde
    xhr.onload = () => {  // cuando llega la respuesta
      if (xhr.status >= 200 && xhr.status < 300) resolve(xhr.response) // si llega bien se resuelve la peticion retornando el response
      else reject(xhr.statusText) // si nos re vimo
    } // poderoso cierre de llavecita
    xhr.onerror = () => reject(xhr.statusText) // si hay error esta todo mal
    xhr.send(OBJ.body) // enviamo la peticion a ver que hondina
  }) // aca cierra la re promesa
} // y aca terminamo la re Request alias peticioncita
