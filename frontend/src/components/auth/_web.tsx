export async function generateKeyPair() {
  const keyPair = await window.crypto.subtle.generateKey(
    {
      name: "ECDSA",
      namedCurve: "P-256",
    },
    true,
    ["sign", "verify"]
  );
  return keyPair;
}

export async function signData(privateKey: CryptoKey, data: string) {
  const encoder = new TextEncoder();
  const encodedData = encoder.encode(data);
  const signature = await window.crypto.subtle.sign(
    {
      name: "ECDSA",
      hash: { name: "SHA-256" },
    },
    privateKey,
    encodedData
  );
  return signature;
}

export async function verifySignature(publicKey: CryptoKey, signature: ArrayBuffer, data: string) {
  const encoder = new TextEncoder();
  const encodedData = encoder.encode(data);
  const isValid = await window.crypto.subtle.verify(
    {
      name: "ECDSA",
      hash: { name: "SHA-256" },
    },
    publicKey,
    signature,
    encodedData
  );
  return isValid;
}
export async function storeKeyInLocalStorage(keyPair: CryptoKeyPair) {
  const publicJWK = await window.crypto.subtle.exportKey("jwk", keyPair.publicKey);
  const privateJWK = await window.crypto.subtle.exportKey("jwk", keyPair.privateKey);

  localStorage.setItem("publicKey", JSON.stringify(publicJWK));
  
  localStorage.setItem("privateKey", JSON.stringify(privateJWK));
}


