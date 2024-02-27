import React, { useState } from "react";

import { Container, Img, Title } from "../styles/styledComponent";
import styled from "styled-components";
import IconArrow from "../assets/image/arrow_r_b.png";
import LoginAuth from "../components/Login/LoginAuth";
import Signup from "../components/Login/Signup";
import { useNavigate } from "react-router-dom";

const LoginScreen = () => {
  const navigation = useNavigate();
  const [idx, setIdx] = useState(1);
  const [run, setRun] = useState(null);
  const [signUp, setSignUp] = useState(false);
  const [phone, setPhone] = useState();

  const onPressNext = (e) => {
    setRun(e);
  };

  const getData = (e) => {
    setIdx(e);
  };

  const onPressSignup = () => {
    setSignUp(true);
  };

  return (
    <Wrap>
      <div className="top">
        <Title color="#222" size={35} weight={700}>
          정보를 <br />
          입력해주세요
        </Title>
        <Title color="#f33562" size={18} weight={500}>
          {idx}{" "}
          <Title color="#7d7d7d" size={18} weight={500}>
            / 3
          </Title>
        </Title>
      </div>
      <div>
        {idx === 1 ? (
          <LoginAuth
            run={run}
            setIdx={setIdx}
            navigation={navigation}
            setData={setPhone}
            setRun={setRun}
          />
        ) : (
          <Signup
            idx={idx}
            getData={getData}
            setRun={setRun}
            signUp={signUp}
            phone={phone}
            run={run}
            navigation={navigation}
          />
        )}

        <NextBtn
          onClick={() => {
            idx === 3 ? onPressSignup() : onPressNext(idx);
          }}
        >
          {idx === 3 ? "완료" : "다음"}
          <Img src={IconArrow} width={12} height={15} left={10} />
        </NextBtn>
      </div>
    </Wrap>
  );
};

const Wrap = styled.div`
  position: relative;
  width: 90%;
  height: 100vh;
  padding-top: 100px;
  margin: auto;
  & .top {
    display: flex;
    justify-content: space-between;
    margin-bottom: 80px;
  }
`;

const NextBtn = styled.button`
  position: absolute;
  bottom: 200px;
  right: 10px;
  font-size: 20px;
`;

export default LoginScreen;
