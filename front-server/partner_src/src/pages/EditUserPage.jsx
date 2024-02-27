import React, { useEffect, useState } from "react";
import apis from "../api/apis";
import NavigationHeader from "../components/Header/NavigationHeader";

import { userState, loginState } from "../recoil/atom";
import { useRecoilState } from "recoil";

const EditUserPage = () => {
  const [userData, setUserData] = useState();
  const [open, setOpen] = useState(false);
  const [, setUser] = useRecoilState(userState);
  const [, setLogin] = useRecoilState(loginState);
  useEffect(() => {
    apis.getUser().then((res) => {
      if (res?.data?.result === "000") {
        setUserData(res.data);
      }
    });
  }, []);

  return (
    <div>
      <NavigationHeader title={"프로필 수정"} value="back" />
    </div>
  );
};

export default EditUserPage;
