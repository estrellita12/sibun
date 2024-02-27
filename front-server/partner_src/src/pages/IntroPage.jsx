import React from "react";

import { styled } from "styled-components";
import { Img, Btn } from "../styles/styledComponent";
import IconLogo from "../assets/image/logo.png";
import IconPhone from "../assets/image/phone.png";

import { useNavigate } from "react-router-dom";

const IntroPage = ({ navigation }) => {
  const navigate = useNavigate();
  return (
    <Container>
      <Logo>
        <Img src={IconLogo} width={160} />
      </Logo>
      <Bottom>
        <UnderLine></UnderLine>
        <Wrap>
          <Title>회원가입/로그인 3초만에 하기</Title>
        </Wrap>
        <Flex>
          <Btn onClick={() => navigate("/login")} bg={"#fff"}>
            <Img src={IconPhone} width={100} />
          </Btn>
        </Flex>
      </Bottom>
    </Container>
  );
};

const Container = styled.div`
  width: 90%;
  margin: auto;
`;

const Flex = styled.div`
  display: flex;
  align-items: center;
  justify-content: center;
`;

const Logo = styled(Flex)`
  height: 60vh;
`;

const Bottom = styled.div`
  height: 40vh;
`;

const UnderLine = styled.div`
  border: 0.5px solid #e8e8e8;
`;

const Wrap = styled(Flex)`
  display: flex;
  align-items: center;
  justify-content: center;

  position: relative;
  top: -13px;

  margin-bottom: 150px;
`;

const Title = styled.span`
  background-color: #fff;
  padding: 0px 20px;
`;
export default IntroPage;
