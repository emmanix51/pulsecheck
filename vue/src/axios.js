import axios from "axios";
import store from "./store";
import router from "./router";

const axiosClient = axios.create({
    baseURL: "http://localhost:8000/api",
    // baseURL: "https://mature-eminent-treefrog.ngrok-free.app/api",
});

axiosClient.interceptors.request.use((config) => {
    // console.log("Sending request with token:", store.state.user.token);
    config.headers.Authorization = `Bearer ${store.state.user.token}`;
    // config.headers["ngrok-skip-browser-warning"] = "true";
    // config.headers["Access-Control-Allow-Origin"] = "true";
    return config;
});

export default axiosClient;

// fetch("https://e10b-180-195-211-174.ngrok-free.app/api/public-surveys", {
//     method: "GET",
//     headers: { "ngrok-skip-browser-warning": "true" },
// })
//
// https://mature-eminent-treefrog.ngrok-free.app/api/
// ngrok http --domain=mature-eminent-treefrog.ngrok-free.app 8000
// npx localtunnel --port 80 (PARA SA MYSQL)
