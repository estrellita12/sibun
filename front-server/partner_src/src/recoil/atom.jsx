import { atom } from "recoil";
import dayjs from "dayjs";

export const userState = atom({
  key: "userState",
  default: [],
});
export const refreshState = atom({
  key: "refreshState",
  default: false,
});

export const storeState = atom({
  key: "storeState",
  default: [],
});

export const storeIdState = atom({
  key: "storeIdState",
  default: null,
});

export const loginState = atom({
  key: "loginState",
  default: null,
});

export const addStoreState = atom({
  key: "addStoreState",
  default: null,
});

export const seletedDayState = atom({
  key: "seletedDayState",
  default: dayjs().format("YYYY-MM-DD"),
});
