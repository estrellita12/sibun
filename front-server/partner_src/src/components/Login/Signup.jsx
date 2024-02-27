import React, { useEffect, useState } from "react";
import {
  LoginInput,
  Btn,
  Wrap,
  Title,
  Container,
  Display,
  Img,
} from "../../styles/styledComponent";

import { styled } from "styled-components";
import Dropdown from "../Dropdown/Dropdown";
import IconDown from "../../assets/image/down.png";
import apis from "../../api/apis";

import { useRecoilState } from "recoil";
import { userState, loginState } from "../../recoil/atom";

const Signup = ({ idx, getData, run, signUp, phone, setRun, navigation }) => {
  const [, setUser] = useRecoilState(userState);
  const [, setLogin] = useRecoilState(loginState);
  const [open, setOpen] = useState(false);

  const [name, setName] = useState();
  const [birth, setBirth] = useState();
  const [emailId, setEmailId] = useState();
  const [email, setEmail] = useState("선택");
  const [gender, setGender] = useState("n");
  const genderlist = [
    { title: "남자", value: "m" },
    { title: "여자", value: "w" },
  ];

  const dropdownData = [
    { title: "naver.com", value: "naver.com" },
    { title: "hanmail.net", value: "hanmail.net" },
    { title: "daum.net", value: "daum.net" },
    { title: "gmail.com", value: "gmail.com" },
    { title: "직접입력", value: "input" },
  ];

  let randomStr = Math.random().toString(36).substring(2, 12);

  function onPressMember() {
    const data = {
      pt_id: randomStr,
      pt_passwd: randomStr,
      pt_name: name,
      pt_cellphone: phone,
      pt_birth: birth,
      pt_gender: gender,
      pt_email: `${emailId}@${email}`,
    };
    console.log(data);

    apis.putUser(data).then((res) => {
      if (res.data.result === "000") {
        alert("회원가입이 완료되었습니다.");
        apis.postLogin({ pt_cellphone: phone }).then((res) => {
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
            setTimeout(() => {
              navigation("StorelistScreen");
            }, 2000);
          }
        });
      }
    });
  }

  useEffect(() => {
    if (run === idx) {
      if (!name || !birth || !gender) {
        alert("입력한 값을 확인해주세요");
        setRun(1);
      } else {
        getData(3);
      }
      // getData({name: name, brith: brith, gender: gender});
    }

    if (signUp) {
      onPressMember();
    }
  }, [run === idx, signUp]);

  const onPressDown = () => {
    setOpen(!open);
  };

  const getDataEmail = () => {};

  return (
    <Container>
      {idx === 2 ? (
        <div>
          <LoginInput
            placeholder="이름"
            onChange={(e) => setName(e.target.value)}
            value={name}
            name="name"
          />
          <LoginInput
            placeholder="8자리 예)19940106"
            onChange={(e) => setBirth(e.target.value)}
            value={birth}
            name="birth"
          />
          <Display content="space-between">
            {genderlist.map((i, k) => {
              const checkValue = gender === i.value;
              return (
                <Box
                  key={k}
                  color={checkValue ? "#f33562" : "#d9d9d9;"}
                  onClick={() => setGender(i.value)}
                >
                  <Title
                    color={checkValue ? "#f33562" : "#222"}
                    size={15}
                    weight={400}
                  >
                    {i.title}
                  </Title>
                </Box>
              );
            })}
          </Display>
        </div>
      ) : (
        <Display content="space-between">
          <LoginInput
            width={45}
            placeholder="이메일"
            onChange={(e) => setEmailId(e.target.value)}
            value={emailId}
            name="emailId"
          />
          <Title bottom={15}>@</Title>
          <Selected onClick={() => setOpen(!open)}>
            <Title color="#444" weight={400} size={14}>
              {email}
            </Title>
            <Img src={IconDown} width={12} height={11} />
          </Selected>
        </Display>
      )}

      {open ? (
        <DropdownWrap>
          <Dropdown
            data={dropdownData}
            width={100}
            setData={setEmail}
            onPressDown={onPressDown}
            getData={getDataEmail}
            align="flex-start"
          />
        </DropdownWrap>
      ) : (
        ""
      )}
    </Container>
  );
};

const Box = styled.button`
  width: 49%;
  height: 50px;
  background-color: #fff;
  border: 1px solid;
  border-color: ${(props) => props.color || "#d9d9d9"};
  border-radius: 5px;
  padding: 0px 20px;
  box-sizing: border-box;
`;

const DropdownWrap = styled.div`
  position: absolute;
  width: 45%;
  right: 0px;
  top: 55px;
`;

const Selected = styled.button`
  width: 45%;
  height: 55px;
  border-radius: 5px;
  margin-bottom: 15px;
  padding: 0px 18px;

  border: 1px solid #d9d9d9;
  display: flex;

  align-items: center;
  justify-content: space-between;
  background-color: #fff;
  box-sizing: border-box;
`;

export default Signup;
