import React from "react";
import { styled } from "styled-components";

import { Title } from "../../styles/styledComponent";
const NavBar = ({ navData, onClickNav, navValue, arrLength, screen }) => {
  return (
    <Container>
      {navData?.map((nav, k) => {
        const countValue = !arrLength[k + 1] ? 0 : arrLength[k + 1];
        return (
          <Nav
            width={k === navValue ? "1px solid #f33562" : 0}
            key={k}
            onClick={() => onClickNav(k)}
          >
            <Title>
              {nav.title}{" "}
              {screen === "main" ? (
                <Title color={k === navValue ? "#f33562 " : "#333"}>
                  {countValue && countValue}
                </Title>
              ) : (
                <Title color={k === navValue ? "#f33562 " : "#333"}>
                  {arrLength(k)}
                </Title>
              )}
            </Title>
          </Nav>
        );
      })}
    </Container>
  );
};

const Container = styled.div`
  position: fixed;
  top: 70px;
  width: 100%;
  max-width: 500px;
  height: 45px;

  background-color: #fff;

  display: grid;
  grid-template-columns: repeat(3, 1fr);
  justify-items: center;

  border-bottom: 1px solid #e9e9e9;

  z-index: 300;
`;

const Nav = styled.button`
  height: 45px;
  line-height: 45px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-bottom: ${(props) => props.width};
  padding: 0px 10px;
`;

export default NavBar;
