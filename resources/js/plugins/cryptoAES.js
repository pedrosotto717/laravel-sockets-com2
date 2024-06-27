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
      const encrypted = CryptoJS.AES.encrypt(JSON.stringify({text}), `message-${email}-key`, {format: CryptoJSAesJson}).toString();

      return encrypted;
  } catch (error) {
      console.error("Encryption error:", error);
      return null; // O manejar de otra manera
  }
};

