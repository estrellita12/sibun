import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import { Title, LoginInput } from "../../styles/styledComponent";
import styled from "styled-components";
import apis from "../../api/apis";

import { useRecoilState } from "recoil";
import { userState, loginState } from "../../recoil/atom";

const LoginAuth = ({ run, setIdx, setData, setRun }) => {
  const navigate = useNavigate();
  const [phone, setPhone] = useState("");
  const [auth, setAuth] = useState("");
  const [, setUser] = useRecoilState(userState);
  const [, setLogin] = useRecoilState(loginState);

  useEffect(() => {
    if (run === 1) {
      onPressCheck();
    }
  }, [run]);

  const onPressSend = (phone) => {
    const data = {
      receiver_cellphone: `${phone}`,
    };
    if (!phone) {
      alert("핸드폰 번호를 입력해주세요.");
    } else {
      apis.postSendsms(data).then((res) => {
        console.log(res);
        if (res.data.result === "000") {
          alert("인증번호가 발송되었습니다.");
        }
      });
    }
  };

  const onPressCheck = () => {
    if (auth !== null) {
      const data = {
        receiver_cellphone: phone,
        auth_code: Number(auth),
      };

      apis.postAuthCheck(data).then((res) => {
        if (res.data.result === "000") {
          apis.postLogin({ pt_cellphone: phone }).then((res) => {
            console.log(res.data);
            if (res.data.result === "000") {
              const user = res.data;
              localStorage.setItem(
                "userInfo",
                JSON.stringify({ ...user.user, refresh: user.refresh_token })
              );
              localStorage.setItem("accessToken", user.access_token);
              localStorage.setItem("refreshToken", user.refresh_token);
              setLogin(true);
              setUser(user?.user);
              alert("인증완료되었습니다.");
              setTimeout(() => {
                navigate("StorelistScreen");
              }, 2000);
            } else {
              setIdx(2);
              setData(phone);
              setRun(null);
            }
          });
        } else {
          alert("인증번호가 잘못되었습니다.");
          setRun(null);
        }
      });
    }
    // const data = {
    //   pt_cellphone: phone,
    // };

    // apis.postLogin(data).then((res) => {
    //   console.log(res);

    //   localStorage.setItem("refreshToken", res?.data?.refresh_token);
    //   localStorage.setItem("accessToken", res?.data?.access_token);

    //   navigate("/storelist");
    // });
  };

  return (
    <Wrap>
      <LoginInput
        placeholder="휴대폰 번호 입력"
        onChange={(e) => setPhone(e.target.value)}
        value={phone}
        name="phone"
      />

      <div className="inputwrap">
        <LoginInput
          placeholder="인증번호"
          onChange={(e) => setAuth(e.target.value)}
          value={auth}
          name="auth"
        />
        <Btn onClick={() => onPressSend(phone)}>
          <Title color="#444" size={14}>
            인증번호 발송
          </Title>
        </Btn>
      </div>
    </Wrap>
  );
};

const Wrap = styled.div`
  & .inputwrap {
    position: relative;
  }
`;

const Btn = styled.button`
  border: 1px solid #d9d9d9;
  border-radius: 50px;
  background-color: #fff;

  width: 100px;
  padding: 8px 0px;

  display: flex;
  align-items: center;
  justify-content: center;

  position: absolute;
  right: 20px;
  top: 12px;
`;
export default LoginAuth;
