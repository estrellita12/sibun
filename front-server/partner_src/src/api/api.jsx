import axios from "axios";
import { getCookie, setCookie } from "../cookie/cookie";

export const api = axios.create({
  baseURL: "http://sibun.co.kr/apip",
  withCredentials: true,
});

export const instance = axios.create({
  baseURL: "http://sibun.co.kr/apip",
});

instance.interceptors.request.use(async (config) => {
  const accessToken = localStorage.getItem("accessToken");
  // const refreshToken = getCookie("refreshToken");
  config.headers.Authorization = `Bearer ${accessToken}`;
  // config.headers.refreshToken = `Bearer ${refreshToken}`;
  return config;
});

instance.interceptors.response.use(
  async (response) => {
    const { config } = response;
    const originalRequest = config;
    const result = response?.data?.result;
    if (result === "020") {
      const refreshToken = localStorage.getItem("refreshToken");

      return api
        .get(`/auth/refresh`, {
          headers: { Authorization: `Bearer ${refreshToken}` },
        })
        .then(async (res) => {
          const result = res?.data;
          if (result?.result === "000") {
            localStorage.setItem("refreshToken", res?.data?.refresh_token);
            localStorage.setItem("accessToken", res?.data?.access_token);
            originalRequest.headers[
              "Authorization"
            ] = `Bearer ${result?.access_token}`;
            return axios(originalRequest);
          } else {
          }
        })
        .catch((err) => {
          console.log(err);
        });
    }

    return response;
  },
  async (error) => {
    console.log(error);
    throw error;
  }
);
