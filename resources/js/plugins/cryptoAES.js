import CryptoJS from "crypto-js";

const CryptoJSAesJson = {
  stringify: function (cipherParams) {
      let j = {ct: cipherParams.ciphertext.toString(CryptoJS.enc.Base64)};
      if (cipherParams.iv) {
        j.iv = cipherParams.iv.toString();
      }
      if (cipherParams.salt) j.s = cipherParams.salt.toString();
      return JSON.stringify(j);
  },

  parse: function (jsonStr) {
      let j = JSON.parse(jsonStr);
      let cipherParams = CryptoJS.lib.CipherParams.create({ciphertext: CryptoJS.enc.Base64.parse(j.ct)});
      if (j.iv) cipherParams.iv = CryptoJS.enc.Hex.parse(j.iv);
      if (j.s) cipherParams.salt = CryptoJS.enc.Hex.parse(j.s);
      return cipherParams;
  }
};


export const cifrarTexto = (texto, email) => {
  if(!texto) {
      return null;
  }

  var textoCifrado = CryptoJS.AES.encrypt(texto, `messaging-${email}-key`).toString();
  var encrypted = CryptoJS.AES.encrypt(JSON.stringify(texto), `messaging-${email}-key`, {format: CryptoJSAesJson}).toString();
  return encrypted;
}


export  const descifrarTexto = (texto, email) => {
  if(!texto) {
      return null;
  }
  
  var decrypted = JSON.parse(CryptoJS.AES.decrypt(texto, `messaging-${email}-key`, {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8));
  return decrypted;
}


export const decryptText = (text, email) => {
  try {
      if (!text) return null;
      const bytes = CryptoJS.AES.decrypt(text, `message-${email}-key`, {format: CryptoJSAesJson});
      const decryptedText = bytes.toString(CryptoJS.enc.Utf8);
      
      return decryptedText;
  } catch (error) {
      console.error("Decryption error:", error);
      return null; // O manejar de otra manera según la lógica de la aplicación
  }
};

export const encryptText = (text, email) => {
  try {
      if (!text) return null;
      return CryptoJS.AES.encrypt(JSON.stringify({text}), `message-${email}-key`, {format: CryptoJSAesJson}).toString();
  } catch (error) {
      console.error("Encryption error:", error);
      return null; // O manejar de otra manera
  }
};

