import { Route, Routes } from "react-router-dom";
import styled from "styled-components";
import "./App.css";
import LoginPage from "./pages/LoginPage";
import IntroPage from "./pages/IntroPage";
import StorelistPage from "./pages/StorelistPage";
import ReservationListPage from "./pages/ReservationListPage";
import ReservationDetailScreen from "./pages/ReservationDetailPage";
import VoucherPage from "./pages/VoucherPage";
import AddVoucherPage from "./pages/AddVoucherPage";
import ReviewPage from "./pages/ReviewPage";
import WriteReviewPage from "./pages/WriteReviewPage";
import SchedulePage from "./pages/SchedulePage";
import KakaoAddress from "./components/KakaoAddress/KakaoAddress";
import SideBar from "./components/SideBar/SideBar";
import AddStorePage from "./pages/AddStorePage";
import AddRoomPage from "./pages/AddRoomPage";
import EditRoomPage from "./pages/EditRoomPage";
import EditStorePricePage from "./pages/EditStorePricePage";
import EditImgPage from "./pages/EditImgPage";
import EditUserPage from "./pages/EditUserPage";

function App() {
  return (
    <Container>
      <Routes>
        <Route path="/" element={<IntroPage />} />
        <Route path="/login" element={<LoginPage />} />
        <Route path="/storelist" element={<StorelistPage />} />
        <Route path="/reservation/:id" element={<ReservationListPage />} />
        <Route
          path="/reservationdetail/:id"
          element={<ReservationDetailScreen />}
        />
        <Route path="/voucher" element={<VoucherPage />} />
        <Route path="/addvoucher" element={<AddVoucherPage />} />
        <Route path="/eidtvoucher/:id" element={<AddVoucherPage />} />
        <Route path="/review" element={<ReviewPage />} />
        <Route path="/review/:id" element={<WriteReviewPage />} />
        <Route path="/schedule" element={<SchedulePage />} />
        <Route path="/addstore" element={<AddStorePage />} />
        <Route path="/eidtstore" element={<AddStorePage />} />
        <Route path="/kakaoadress" element={<KakaoAddress />} />
        <Route path="/navbar" element={<SideBar />} />
        <Route path="/addroom" element={<AddRoomPage />} />
        <Route path="/editroom" element={<EditRoomPage />} />
        <Route path="/editprice" element={<EditStorePricePage />} />
        <Route path="/editimg" element={<EditImgPage />} />
        <Route path="/edituser" element={<EditUserPage />} />
      </Routes>
    </Container>
  );
}

const Container = styled.div`
  min-width: 350px;
  max-width: 500px;
  width: 100vw;
  /* height: 100vh; */
  margin: auto;
  box-sizing: border-box;
  overflow-x: hidden;
`;

export default App;
